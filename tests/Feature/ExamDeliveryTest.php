<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExamDeliveryTest extends TestCase
{
    use RefreshDatabase;

    private User $teacherUser;
    private User $studentUser;
    private Student $student;
    private Subject $subject;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');

        $this->subject = Subject::query()->create(['module_id' => 'M1', 'name' => 'Maths', 'lectures_count' => 10, 'assigned_lecturers' => 'T', 'enrol_key' => 'mk']);

        $teacherAttrs = array_intersect_key([
            'name' => 'T', 'email' => 't@x.test', 'phone_number' => '1', 'address' => 'a',
            'birth_date' => '1990-01-01', 'image' => 'i', 'password' => 'x', 'reg_no' => 'T1', 'teacher_id' => 'T1', 'lecturer_id' => 'L1', 'age' => '30', 'subjects' => 'Maths',
        ], array_flip(\Illuminate\Support\Facades\Schema::getColumnListing('teachers')));
        Teacher::unguard();
        Teacher::query()->create($teacherAttrs);
        $this->teacherUser = User::query()->create(['name' => 'T', 'email' => 't@x.test', 'role' => 'teacher', 'password' => 'x']);

        $this->student = Student::query()->create(['reg_no' => 'S1', 'name' => 'S', 'phone_number' => '1', 'email' => 's@x.test', 'address' => 'a', 'birth_date' => '2000-01-01', 'image' => 'i', 'password' => 'x']);
        $this->student->subjects()->attach($this->subject->id);
        $this->studentUser = User::query()->create(['name' => 'S', 'email' => 's@x.test', 'role' => 'student', 'password' => 'x']);
    }

    private function base(array $extra): array
    {
        return array_merge([
            'title' => 'Mid', 'subject_id' => $this->subject->id, 'exam_date' => '2026-12-01',
            'enrol_key' => 'secret', 'duration_minutes' => 30, 'pass_marks' => 1,
        ], $extra);
    }

    public function test_quiz_flow(): void
    {
        $this->actingAs($this->teacherUser)->post(route('exam.createExam'), $this->base([
            'exam_type' => 'quiz', 'total_marks' => 5,
            'questions' => [
                ['text' => '1+1?', 'options' => ['2', '', '3', ''], 'correct' => [0], 'marks' => 2],
                ['text' => '2+2?', 'options' => ['', '3', '', '4'], 'correct' => [3], 'marks' => 3],
            ],
        ]))->assertSessionHasNoErrors()->assertRedirect(route('exam.exam_list'));

        $exam = Exam::query()->firstOrFail();
        $this->assertSame('quiz', $exam->exam_type);
        $this->assertSame(5, (int) $exam->total_marks);
        $q2 = $exam->questions()->orderBy('id')->get()[1];
        $this->assertSame(['3', '4'], $q2->options);
        $this->assertSame([1], $q2->correct_options);

        $this->actingAs($this->teacherUser)->get(route('exam.edit', $exam->id))->assertOk()->assertSee('2+2?');

        // student: wrong key, right key, take, submit
        $this->actingAs($this->studentUser)->get(route('student.dashboard'))->assertOk()->assertSee('Mid');
        $this->actingAs($this->studentUser)->get(route('student.view_module', $this->subject->id))->assertOk()->assertSee('Quiz');
        $this->actingAs($this->studentUser)->post(route('student.exam.start', $exam->id), ['enrol_key' => 'nope'])->assertSessionHas('error');
        $this->assertSame(0, ExamAttempt::query()->count());
        $this->actingAs($this->studentUser)->post(route('student.exam.start', $exam->id), ['enrol_key' => 'secret'])->assertRedirect(route('student.exam.take', $exam->id));

        $take = $this->actingAs($this->studentUser)->get(route('student.exam.take', $exam->id))->assertOk()->assertSee('1+1?');
        $take->assertDontSee('correct_options');

        $q1 = $exam->questions()->orderBy('id')->first();
        $this->actingAs($this->studentUser)->post(route('student.exam.submit', $exam->id), [
            'answers' => [$q1->id => [0], $q2->id => [0]],
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('exam_results', ['exam_id' => $exam->id, 'student_id' => $this->student->id, 'marks' => 2]);
        $this->actingAs($this->studentUser)->get(route('student.exam.show', $exam->id))->assertOk()->assertSee('2 / 5', false);

        // questions locked once attempted
        $this->actingAs($this->teacherUser)->post(route('exam.update'), $this->base([
            'id' => $exam->id, 'exam_type' => 'quiz', 'total_marks' => 1,
            'questions' => [['text' => 'x', 'options' => ['a', 'b'], 'correct' => [0], 'marks' => 1]],
        ]))->assertSessionHasErrors('questions');
    }

    public function test_question_with_several_correct_answers(): void
    {
        $this->actingAs($this->teacherUser)->post(route('exam.createExam'), $this->base([
            'exam_type' => 'quiz', 'total_marks' => 10,
            'questions' => [
                // blank option B is dropped, so C / D become indexes 1 / 2
                ['text' => 'Primes?', 'options' => ['2', '', '3', '4'], 'correct' => [0, 2], 'marks' => 5],
                ['text' => 'Even?', 'options' => ['1', '2'], 'correct' => [1], 'marks' => 5],
            ],
        ]))->assertSessionHasNoErrors();

        $exam = Exam::query()->firstOrFail();
        [$multi, $single] = $exam->questions()->orderBy('id')->get()->all();
        $this->assertSame([0, 1], $multi->correct_options);

        // the edit form ticks both correct answers again
        $this->actingAs($this->teacherUser)->get(route('exam.edit', $exam->id))->assertOk()->assertSee('"correct":[0,1]', false);

        $this->actingAs($this->studentUser)->post(route('student.exam.start', $exam->id), ['enrol_key' => 'secret']);
        $take = $this->actingAs($this->studentUser)->get(route('student.exam.take', $exam->id))->assertOk();
        $take->assertSee('Select all that apply.');
        $take->assertSee('type="checkbox"', false);
        $take->assertSee('type="radio"', false);

        // only part of the correct answers = no marks for that question
        $this->assertFalse($multi->isCorrect([0]));
        $this->assertFalse($multi->isCorrect([0, 1, 2]));
        $this->assertTrue($multi->isCorrect(['1', '0']));

        $this->actingAs($this->studentUser)->post(route('student.exam.submit', $exam->id), [
            'answers' => [$multi->id => [1, 0], $single->id => [0]],
        ])->assertSessionHas('success');
        $this->assertDatabaseHas('exam_results', ['exam_id' => $exam->id, 'marks' => 5]);
    }

    public function test_quiz_marks_must_add_up_to_full_marks(): void
    {
        $questions = [
            ['text' => 'a', 'options' => ['x', 'y'], 'correct' => [0], 'marks' => 33.33, 'auto' => '1'],
            ['text' => 'b', 'options' => ['x', 'y'], 'correct' => [0], 'marks' => 33.33, 'auto' => '1'],
            ['text' => 'c', 'options' => ['x', 'y'], 'correct' => [1], 'marks' => 33.34, 'auto' => '1'],
        ];

        // more than full marks
        $this->actingAs($this->teacherUser)->post(route('exam.createExam'), $this->base([
            'exam_type' => 'quiz', 'total_marks' => 90, 'questions' => $questions,
        ]))->assertSessionHasErrors(['questions' => 'The question marks add up to 100, which is 10 more than the full marks (90).']);

        // less than full marks
        $this->actingAs($this->teacherUser)->post(route('exam.createExam'), $this->base([
            'exam_type' => 'quiz', 'total_marks' => 120, 'questions' => $questions,
        ]))->assertSessionHasErrors(['questions' => 'The question marks add up to 100, which is 20 less than the full marks (120).']);
        $this->assertSame(0, Exam::query()->count());

        // decimal default marks that add up exactly
        $this->actingAs($this->teacherUser)->post(route('exam.createExam'), $this->base([
            'exam_type' => 'quiz', 'total_marks' => 100, 'questions' => $questions,
        ]))->assertSessionHasNoErrors();
        $this->assertEquals([33.33, 33.33, 33.34], Exam::query()->first()->questions()->orderBy('id')->pluck('marks')->map(fn ($m) => (float) $m)->all());

        // a correct answer is required
        $questions[0]['correct'] = null;
        $this->actingAs($this->teacherUser)->post(route('exam.createExam'), $this->base([
            'exam_type' => 'quiz', 'total_marks' => 100, 'questions' => $questions,
        ]))->assertSessionHasErrors('questions.0.correct');
    }

    public function test_description_is_shown_as_rule_list(): void
    {
        $this->actingAs($this->teacherUser)->post(route('exam.createExam'), $this->base([
            'exam_type' => 'quiz', 'total_marks' => 1,
            'description' => "1. Answer all questions\r\n\r\n- No phones\n* Work alone",
            'questions' => [['text' => 'q', 'options' => ['a', 'b'], 'correct' => [0], 'marks' => 1]],
        ]))->assertSessionHasNoErrors();
        $exam = Exam::query()->firstOrFail();

        $this->assertSame(['Answer all questions', 'No phones', 'Work alone'], $exam->rules());

        $this->actingAs($this->studentUser)->get(route('student.exam.show', $exam->id))->assertOk()
            ->assertSeeInOrder(['From your lecturer', 'Answer all questions', 'No phones', 'Work alone', 'Exam rules', '30 minutes']);

        $this->actingAs($this->studentUser)->post(route('student.exam.start', $exam->id), ['enrol_key' => 'secret']);
        $this->actingAs($this->studentUser)->get(route('student.exam.take', $exam->id))->assertOk()->assertSee('No phones');
    }

    public function test_review_shows_only_wrong_questions_with_correct_answers(): void
    {
        $this->actingAs($this->teacherUser)->post(route('exam.createExam'), $this->base([
            'exam_type' => 'quiz', 'total_marks' => 3, 'review_mode' => 'immediate',
            'questions' => [
                ['text' => 'Right one?', 'options' => ['yes', 'no'], 'correct' => [0], 'marks' => 1],
                ['text' => 'Wrong one?', 'options' => ['apple', 'banana'], 'correct' => [1], 'marks' => 1],
                ['text' => 'Skipped one?', 'options' => ['x', 'y'], 'correct' => [0], 'marks' => 1],
            ],
        ]))->assertSessionHasNoErrors();
        $exam = Exam::query()->firstOrFail();
        [$q1, $q2] = $exam->questions()->orderBy('id')->get()->all();

        $this->actingAs($this->studentUser)->post(route('student.exam.start', $exam->id), ['enrol_key' => 'secret']);

        // not available before submitting
        $this->actingAs($this->studentUser)->get(route('student.exam.review', $exam->id))->assertNotFound();

        $this->actingAs($this->studentUser)->post(route('student.exam.submit', $exam->id), [
            'answers' => [$q1->id => [0], $q2->id => [0]],
        ])->assertSessionHas('success');

        $this->actingAs($this->studentUser)->get(route('student.exam.show', $exam->id))
            ->assertSee(route('student.exam.review', $exam->id), false);

        $this->actingAs($this->studentUser)->get(route('student.exam.review', $exam->id))->assertOk()
            ->assertDontSee('Right one?')
            ->assertSeeInOrder(['Wrong one?', 'apple', 'Your answer', 'banana', 'Skipped one?', 'You did not answer this question.'])
            ->assertSee('1 of 3 questions correct');
    }

    private function submittedQuiz(array $extra): Exam
    {
        $this->actingAs($this->teacherUser)->post(route('exam.createExam'), $this->base(array_merge([
            'exam_type' => 'quiz', 'total_marks' => 1,
            'questions' => [['text' => 'q', 'options' => ['a', 'b'], 'correct' => [0], 'marks' => 1]],
        ], $extra)))->assertSessionHasNoErrors();
        $exam = Exam::query()->firstOrFail();

        $this->actingAs($this->studentUser)->post(route('student.exam.start', $exam->id), ['enrol_key' => 'secret']);
        $this->actingAs($this->studentUser)->post(route('student.exam.submit', $exam->id), ['answers' => []]);

        return $exam;
    }

    public function test_review_is_off_by_default(): void
    {
        $exam = $this->submittedQuiz([]);
        $this->assertSame('never', $exam->review_mode);

        $this->actingAs($this->studentUser)->get(route('student.exam.show', $exam->id))
            ->assertDontSee('Review incorrect answers');
        $this->actingAs($this->studentUser)->get(route('student.exam.review', $exam->id))
            ->assertRedirect(route('student.exam.show', $exam->id))->assertSessionHas('error');
    }

    public function test_review_opens_on_the_chosen_date(): void
    {
        // a date is required for the "date" mode
        $this->actingAs($this->teacherUser)->post(route('exam.createExam'), $this->base([
            'exam_type' => 'quiz', 'total_marks' => 1, 'review_mode' => 'date',
            'questions' => [['text' => 'q', 'options' => ['a', 'b'], 'correct' => [0], 'marks' => 1]],
        ]))->assertSessionHasErrors('review_available_at');

        $exam = $this->submittedQuiz(['review_mode' => 'date', 'review_available_at' => now()->addDay()->format('Y-m-d\TH:i')]);

        $this->actingAs($this->studentUser)->get(route('student.exam.show', $exam->id))
            ->assertDontSee('Review incorrect answers')
            ->assertSee('You can review your incorrect answers from');
        $this->actingAs($this->studentUser)->get(route('student.exam.review', $exam->id))
            ->assertRedirect(route('student.exam.show', $exam->id));

        $this->travel(2)->days();
        $this->actingAs($this->studentUser)->get(route('student.exam.show', $exam->id))
            ->assertSee('Review incorrect answers');
        $this->actingAs($this->studentUser)->get(route('student.exam.review', $exam->id))
            ->assertOk()->assertSee('You did not answer this question.');

        // the teacher's edit form keeps the setting
        $this->actingAs($this->teacherUser)->get(route('exam.edit', $exam->id))
            ->assertSee('value="date" selected', false)
            ->assertSee($exam->review_available_at->format('Y-m-d\TH:i'));
    }

    public function test_late_quiz_submit_scores_zero(): void
    {
        $this->actingAs($this->teacherUser)->post(route('exam.createExam'), $this->base([
            'exam_type' => 'quiz', 'total_marks' => 1,
            'questions' => [['text' => 'q', 'options' => ['a', 'b'], 'correct' => [0], 'marks' => 1]],
        ]))->assertSessionHasNoErrors();
        $exam = Exam::query()->firstOrFail();

        $this->actingAs($this->studentUser)->post(route('student.exam.start', $exam->id), ['enrol_key' => 'secret']);
        ExamAttempt::query()->update(['started_at' => now()->subMinutes(45)]);

        $q = $exam->questions()->first();
        $this->actingAs($this->studentUser)->post(route('student.exam.submit', $exam->id), ['answers' => [$q->id => [0]]])
            ->assertSessionHas('error');
        $this->assertDatabaseHas('exam_results', ['exam_id' => $exam->id, 'marks' => 0]);
    }

    public function test_paper_flow(): void
    {
        $this->actingAs($this->teacherUser)->post(route('exam.createExam'), $this->base([
            'exam_type' => 'paper', 'total_marks' => 100, 'pass_marks' => 40,
        ]))->assertSessionHasErrors('paper');

        $this->actingAs($this->teacherUser)->post(route('exam.createExam'), $this->base([
            'exam_type' => 'paper', 'total_marks' => 100, 'pass_marks' => 40,
            'paper' => UploadedFile::fake()->create('paper.pdf', 100, 'application/pdf'),
        ]))->assertSessionHasNoErrors();

        $exam = Exam::query()->firstOrFail();
        Storage::disk('local')->assertExists($exam->paper_path);
        $this->actingAs($this->teacherUser)->get(route('exam.exam_list'))->assertOk()->assertSee('PDF paper');

        // paper hidden until the key is entered
        $this->actingAs($this->studentUser)->get(route('student.exam.paper', $exam->id))->assertNotFound();
        $this->actingAs($this->studentUser)->post(route('student.exam.start', $exam->id), ['enrol_key' => 'secret']);
        $this->actingAs($this->studentUser)->get(route('student.exam.take', $exam->id))->assertOk()->assertSee('Exam paper');
        $this->actingAs($this->studentUser)->get(route('student.exam.paper', $exam->id))->assertOk();

        ExamAttempt::query()->update(['started_at' => now()->subHours(2)]);
        $this->actingAs($this->studentUser)->get(route('student.exam.paper', $exam->id))->assertForbidden();

        // switching to quiz removes the stored pdf
        $old = $exam->paper_path;
        ExamAttempt::query()->delete();
        $this->actingAs($this->teacherUser)->post(route('exam.update'), $this->base([
            'id' => $exam->id, 'exam_type' => 'quiz', 'total_marks' => 4,
            'questions' => [['text' => 'q', 'options' => ['a', 'b'], 'correct' => [1], 'marks' => 4]],
        ]))->assertSessionHasNoErrors();
        Storage::disk('local')->assertMissing($old);
    }

    public function test_student_not_enrolled_gets_404(): void
    {
        $other = Subject::query()->create(['module_id' => 'M2', 'name' => 'Art', 'lectures_count' => 1, 'assigned_lecturers' => 'T', 'enrol_key' => 'k']);
        $this->actingAs($this->teacherUser)->post(route('exam.createExam'), array_merge($this->base([
            'exam_type' => 'quiz', 'total_marks' => 1,
            'questions' => [['text' => 'q', 'options' => ['a', 'b'], 'correct' => [0], 'marks' => 1]],
        ]), ['subject_id' => $other->id]))->assertSessionHasNoErrors();

        $this->actingAs($this->studentUser)->get(route('student.exam.show', Exam::query()->first()->id))->assertNotFound();
    }
}
