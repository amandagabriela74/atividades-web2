<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use App\Models\Borrowing; 

class BorrowingController extends Controller
{
    public function store(Request $request, Book $book)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        $user = User::find($request->user_id);

         // Verifica empréstimo aberto no pivot (borrowings) do livro
        if ($book->hasOpenBorrowing()) {
            return redirect()->back()->withErrors('Este livro já está emprestado e não foi devolvido.');
        }

        if ($user->openBorrowingsCount() >= 5) {
            return redirect()->back()->withErrors('O usuário já possui o limite máximo de 5 livros emprestados.');
        }
        
        if ($user->debit > 0) {
            return redirect()->back()->withErrors('Usuário possui débitos pendentes e não pode realizar empréstimos.');
        }

        Borrowing::create([
            'user_id' => $request->user_id,
            'book_id' => $book->id,
            'borrowed_at' => now(),
        ]);

        return redirect()->route('books.show', $book)->with('success', 'Empréstimo registrado com sucesso.');
    }

    public function returnBook(Borrowing $borrowing)
    {
        $now = now();
        $borrowedAt = $borrowing->borrowed_at;
        $diffDays = $borrowedAt->diffInDays($now);

        $fine = 0;

        if ($diffDays > 15) {
            $lateDays = $diffDays - 15;
            $fine = $lateDays * 0.50;

            // Atualiza débito do usuário
            $user = $borrowing->user;
            $user->debit += $fine;
            $user->save();
        }

        // Marca devolução
        $borrowing->update([
            'returned_at' => $now,
        ]);

        $message = 'Devolução registrada com sucesso.';
        if ($fine > 0) {
            $message .= ' Multa aplicada: R$ ' . number_format($fine, 2);
        }

        return redirect()->route('books.show', $borrowing->book_id)->with('success', $message);
    }


    public function userBorrowings(User $user)
    {
        $borrowings = $user->books()->withPivot('borrowed_at', 'returned_at')->get();

        return view('users.borrowings', compact('user', 'borrowings'));
    }

}
