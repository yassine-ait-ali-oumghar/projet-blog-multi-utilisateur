<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AssistantController extends Controller
{
    public function ask(Request $request)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'min:2', 'max:1200'],
        ]);

        $question = trim($validated['question']);
        $apiKey = config('services.openai.key');

        if (!empty($apiKey)) {
            try {
                $response = Http::withToken($apiKey)
                    ->timeout(25)
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model' => 'gpt-4o-mini',
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => 'You are Nexus Intelligence, a professional AI assistant for the Nexus blog platform. Answer in French, professional, clear, and helpful. If user asks non-blog questions, answer normally.',
                            ],
                            [
                                'role' => 'user',
                                'content' => $question,
                            ],
                        ],
                    ]);

                if ($response->successful()) {
                    $answer = $response->json('choices.0.message.content');
                    if (is_string($answer) && $answer !== '') {
                        return response()->json(['answer' => $answer]);
                    }
                }
            } catch (\Throwable $exception) {
                // Fall back to local responder below.
            }
        }

        $answer = $this->localAssistantReply($question);

        return response()->json(['answer' => $answer]);
    }

    private function localAssistantReply(string $question): string
    {
        $q = mb_strtolower($question);
        $postsCount = Post::count();

        if (str_contains($q, 'follow')) {
            return "Pour suivre un utilisateur, clique sur le bouton \"➕ Follow\" a cote de son nom dans le feed. "
                ."Ensuite, l'onglet Following affiche seulement les posts des personnes suivies.";
        }

        if (str_contains($q, 'j adore') || str_contains($q, 'like')) {
            return "Le bouton ❤️ ajoute ou retire votre j'adore. L'auteur du post recoit une notification automatique.";
        }

        if (str_contains($q, 'republication') || str_contains($q, 'repost')) {
            return "Le bouton 🔁 republication partage le post dans l'activite. L'auteur du post recoit aussi une notification.";
        }

        if (str_contains($q, 'notification')) {
            return "Dans Notifications, vous recevez les messages quand quelqu'un aime, reposte, ou commence a vous suivre.";
        }

        if (str_contains($q, 'explore') || str_contains($q, 'top like')) {
            return "Explore affiche les posts tries par nombre de j'adore (Top likes).";
        }

        if (str_contains($q, 'post') || str_contains($q, 'publier')) {
            return "Pour publier: clique sur \"Post Article\", ecris le titre et la description, puis publie. "
                ."Il y a actuellement {$postsCount} posts publics sur le blog.";
        }

        return "Je peux vous aider sur toutes les questions du blog et sur des questions generales. "
            ."Si vous voulez des reponses de niveau \"Meta AI\", ajoutez une cle dans .env: OPENAI_API_KEY=... "
            ."En attendant, je reponds en mode local. Question recue: \"{$question}\".";
    }
}
