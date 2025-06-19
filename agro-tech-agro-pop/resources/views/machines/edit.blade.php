<x-layout title='Editar Maquina'>

    <a href="{{route('machines.index')}}">Listar</a>

    <h1>Editar Máquina</h1>

    <form action="{{route('machines.update', $machine->id)}}" method="POST">
        @csrf
        @method('PUT')
        <label>Nome: </label>
        <input type="text" name="name" id="name" placeholder="Nome da máquina" value="{{$machine->name}}"><br><br>

        <button type="submit">Editar</button>
    </form>

</x-layout>