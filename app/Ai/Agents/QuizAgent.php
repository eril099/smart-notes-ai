<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

class QuizAgent implements Agent, Conversational, HasStructuredOutput, HasTools
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'Kamu adalah pembuat soal pembelajaran yang ahli.
        Buat 5 soal pilihan ganda berdasarkan materi yang diberikan.
        Setiap soal HARUS memiliki:
        - question: pertanyaan yang jelas
        - option_a: pilihan jawaban A
        - option_b: pilihan jawaban B
        - option_c: pilihan jawaban C
        - option_d: pilihan jawaban D
        - answer: kunci jawaban yang benar (hanya A, B, C, atau D)
        Pastikan soal bervariasi dan menguji pemahaman materi.
        Gunakan bahasa Indonesia.';
    }

    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return [];
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [];
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'question' => $schema
            ->array()
            ->items(
                $schema->object(
                    fn($schema) => [
                        'question' => $schema->string()->required(),
                        'option_a' => $schema->string()->required(),
                        'option_b' => $schema->string()->required(),
                        'option_c' => $schema->string()->required(),
                        'option_d' => $schema->string()->required(),
                        'answer' => $schema->string()->enum([
                            'A',
                            'B',
                            'C',
                            'D'
                        ])->required(),
                    ]
                )
            )
            ->required(),
        ];
    }
}
