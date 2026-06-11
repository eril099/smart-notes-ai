<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

class ChatAgent implements Agent, Conversational, HasTools
{
    use Promptable;

    /**
     * The conversation messages history.
     *
     * @var Message[]
     */
    protected array $conversationMessages = [];

    /**
     * Set the conversation history messages.
     *
     * @param Message[] $messages
     */
    public function withMessages(array $messages): static
    {
        $this->conversationMessages = $messages;

        return $this;
    }

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'Kamu adalah asisten AI yang cerdas dan ramah bernama Smart Notes AI Assistant.
        Tugasmu adalah membantu pengguna dengan berbagai pertanyaan dan diskusi.
        Berikan jawaban yang jelas, informatif, dan mudah dipahami.
        Gunakan bahasa Indonesia yang baik dan benar.
        Kamu bisa membantu dengan:
        - Menjawab pertanyaan umum
        - Menjelaskan konsep-konsep
        - Membantu belajar dan memahami materi
        - Berdiskusi tentang berbagai topik
        Berikan jawaban dalam format markdown jika diperlukan untuk kejelasan.';
    }

    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return $this->conversationMessages;
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
}
