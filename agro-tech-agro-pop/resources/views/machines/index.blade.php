<x-layout title='Listar as Maquinas'>

    <a href="{{route('machines.create')}}">Cadastrar</a>

    <h1>Listar as máquinas</h1>

    @include('components/flash-message')

    <table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($machines as $machine)
            <tr>
                <td>{{$machine->id}}</td>
                <td>{{$machine->name}}</td>
                <td style="display: flex;">
                    <button type="button"><a href="{{route('machines.show', $machine->id)}}">Visualizar</a></button>&nbsp;
                    <button type="button"><a href="{{route('machines.edit', $machine->id)}}">Editar</a></button>&nbsp;

                    <form action="{{route('machines.destroy', $machine->id)}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Apagar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>


</x-layout>