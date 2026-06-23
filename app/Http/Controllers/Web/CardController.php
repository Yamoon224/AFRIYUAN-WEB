<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\UserCard;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    public function __construct(private StripeService $stripe) {}

    public function index()
    {
        $user  = Auth::user();
        $cards = $user->cards()->orderByDesc('is_default')->orderByDesc('created_at')->get();

        $setupIntentSecret = null;
        try {
            $setupIntentSecret = $this->stripe->createSetupIntent($user)->client_secret;
        } catch (\Exception) {}

        return view('cards.index', compact('cards', 'setupIntentSecret'));
    }

    public function store(Request $request)
    {
        $request->validate(['payment_method_id' => 'required|string']);
        $user = Auth::user();

        try {
            $this->stripe->attachPaymentMethod($user, $request->payment_method_id);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'ajout de la carte : ' . $e->getMessage());
        }

        return redirect()->route('cards.index')->with('success', 'Carte ajoutée avec succès.');
    }

    public function setDefault(UserCard $card)
    {
        abort_unless($card->user_id === Auth::id(), 403);

        Auth::user()->cards()->update(['is_default' => false]);
        $card->update(['is_default' => true]);

        return redirect()->route('cards.index')->with('success', 'Carte par défaut mise à jour.');
    }

    public function destroy(UserCard $card)
    {
        abort_unless($card->user_id === Auth::id(), 403);

        try {
            $this->stripe->detachPaymentMethod($card->stripe_payment_method_id);
        } catch (\Exception) {}

        $card->delete();

        return redirect()->route('cards.index')->with('success', 'Carte supprimée.');
    }
}
