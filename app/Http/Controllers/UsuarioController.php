<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UsuarioController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function index()
    {
        $usuarios = User::orderBy('name')->paginate(10); // Paginação 10 por página
        return view('usuarios.index', compact('usuarios'));
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user); // Garante que só admin possa deletar
        $user->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuário deletado com sucesso!');
    }

    public function edit(User $user)
    {
        // Apenas admin pode editar todos os usuários, ou cada usuário pode editar o próprio
        $this->authorize('update', $user);

        return view('usuarios.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'perfil' => 'required|in:admin,medico,recepcao',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->perfil = $validated['perfil'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('usuarios.index', $user)->with('success', 'Usuário atualizado com sucesso!');
    }

    public function create()
    {
        return view('usuarios.create'); // View para o formulário
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'perfil' => 'required|in:admin,medico,secretaria', // ajuste conforme seu sistema
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'perfil' => $request->perfil,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuário criado com sucesso!');
    }
}
