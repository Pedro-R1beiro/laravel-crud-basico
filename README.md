# CRUD de Máquinas Agrícolas com Laravel

> Projeto de estudo com Laravel, aplicando as operações básicas de banco de dados (Create, Read, Update, Delete) com tema voltado à agricultura.

## 🚀 Tecnologias Utilizadas

- PHP
- Laravel
- MySQL

|O que aprendi|
|---|
|[Configurações inicias](#configuracoes-iniciais)|
|[Seeder](#seeder)|
|[Factory](#factory)|
|[CRUD](#crud)|


# Configurações Iniciais

### Comando para criar projeto Laravel
``` bash
    composer create-project laravel/laravel nome-do-projeto
``` 
### Iniciar projeto
``` bash
    php artisan serve
``` 
### Conectar ao banco de dados
No arquivo:
``` http
    config/database.php
```
fornece configurações já definidas para alguns banco de dados.

Para configurar o seu banco de dados, basta ir no arquivo:
``` http
    .env
```
e substituir as variáveis de ambiente respectivas ao banco de dados, que estão nas linhas 23-28.

### Criação de tabelas
Algumas tabelas padrões já são fornecidas, no arquivo:
``` http
    database/migrations
```
Para inicializar essas tabelas, use:
``` bash
    php artisan migrate
```
Caso deseje adicionar uma tabela, use o comando:
``` bash
    php artisan make:migration create_nome_tabela
```
Em seguida vá no arquivo: 
``` http
    database/migrations/create_nome_tabela.php
```
e configure a tabela. Na criação dela podem ser colocados os campos desejados no método 'up'. No meu projeto fiz:
``` PHP
    public function up(): void
    {
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->string('name', 220);
            $table->timestamps();
        });
    }
```
O 'id' já determina como auto_increment, a string cria um campo varchar com o tamanho determinado e o timestamps cria dois campos, o created_at e o updated_at.

## Alguns problemas que tive:

Durante esse mini projeto eu tive dois problemas, um que realmente afetava no desenvolvimento, já outro que afetava mais na leitura dos dados. 

### Problema 1: Erro no sql

Logo quando tentei executar o comando para criar as migrations, recebi o erro: **SQLSTATE[42000]: Syntax error or access violation: 1071 Specified key was too long; max key length is 1000 bytes**. Que de forma resumida, o tamanho do dado superava o tamanho que o MySql suportava. Pra resolver isso, recorri ao ChatGPT, após uma explicação sobre este erro, ele forneceu uma solução. Basicamente eu precisei ir até onde era definido o tamanho padrão das strings varchar ao criar um campo pelo migration, no arquivo: 
``` http
    vendor\laravel\framework\src\Illuminate\Database\Schema\Builder.php
```
na linha 42, substitui isso:
``` PHP
    public static $defaultStringLength = 255;
```
por:
``` PHP
    public static $defaultStringLength = 191;
```

Não sei se era a melhor opção, mas resolveu.

### Problema 2: Timezone errada

Esse não era algo que afetava na execução, mas poderia afetar no gerenciamento dos dados. A timezone estava como 'UTC', dava um adiantamento de 3 horas do horário de Brasília, se eu criasse um registro às 20 horas, no banco de dados era registrado como se tivesse sido criado às 23 horas.

Para resolver isso fui até o arquivo:
``` http
    config/app.php
```
na linha 68, na parte de timezone apenas substitui ela da seguinte forma:
``` PHP
    'timezone' => 'UTC',
    // Troquei por:
    'timezone' => 'America/Sao_Paulo',
```
# Seeder

Seeder é uma classe para adicionar dados iniciais ou de testes no banco de dados.

Código de criação:
``` bash
    php artisan make:seeder NomeSeeder
```
<sub>Aconselhado colocar o mesmo nome do model (tabela ou controller)</sub>

Será criado um arquivo em:
``` http
    database/seeders/NomeSeeder 
```
Dentro deste arquivo, na function 'run', importe o model referente, adicione o método create e coloque os campos e os valores para preencher, ex.:
``` PHP
    public function run(): void
    {
        Machine::create([
            'name' => 'Colheitadeira'
        ]);
    }
```
Após isso, em: 
``` bash
    database/seeders/DatabaseSeeder.php
```
Na function run faça:
``` PHP
    public function run(): void
    {
        $this->call([
           MachinesSeeder::class 
        ]);
    }
```
Caso tenha mais seeders, só adicionar no array, ex.:
``` PHP
     $this->call([
     	MachinesSeeder::class,
     	ExemploSeeder::class
     ]);
```
Comando para executar seeders: 
``` bash
    php artisan db:seed
```

# Factory

Factory gera uma quantidade de valores aleatórios para preencher o banco de dados.

Comando para criar factory: 
``` bash
    php artisan make:factory NomeFactory (Aconselhado usar o nome do model)
```
A factory será criada em:
``` http
    database/factories/NomeFactory
```
Para determinar quais valores devem ser preenchidos, acesse o método 'definition', no return adicione os campos:
``` PHP
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word()
        ];
    }
```
No unique pode colocar outros tipos de dados (color, address, etc). Dados já fornecidos pelo framework.

Para executar, no arquivo da seeder, após: 
``` PHP
    $this->call([
           MachinesSeeder::class 
        ]);
```
Adiciona: 
``` PHP
    Machine::factory(count: 100)->create(); // 100 é a quantidade de registros que será criada
```

# CRUD
CRUD é a sigla para Create, Read, Update e Delete – as quatro operações básicas de persistência em um banco de dados. Abaixo, explico como implementei essas operações usando o Laravel:

## Model
O model representa a tabela no banco de dados e define os campos que podem ser preenchidos. No meu caso, o model está em:

```http
    app/Models/Machine.php
```
``` PHP
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model {
    use HasFactory;

    protected $fillable = ['name'];
}
```
A propriedade $fillable define quais campos podem ser preenchidos em massa (ex: via create() ou fill()).

## Controller
O controller gerencia as requisições relacionadas às máquinas. Está localizado em:

```http
    app/Http/Controllers/MachinesController.php
```
Abaixo o que cada método faz:

### index()
Lista todas as máquinas do banco de dados.

``` php
    $machines = Machine::all();
    return view('machines.index')->with('machines', $machines);
```
### show(Machine $machine)

Exibe os detalhes de uma máquina específica.

### create()
Retorna a view com o formulário para cadastrar uma nova máquina.

### store(Request $request)

Salva a nova máquina no banco.

``` php
    Machine::create($request->only(['name']));
```
### edit(Machine $machine)
Carrega o formulário para editar uma máquina existente.

### update(Request $request, Machine $machine)
Atualiza os dados de uma máquina.

``` php
    $machine->fill($request->all())->save();
```
### destroy(Machine $machine)
Exclui uma máquina do banco de dados.

``` php
    $machine->delete();
```

## Rotas
As rotas estão definidas no arquivo:

``` http
    routes/web.php
```
Usei o método Route::resource, que já define todas as rotas padrão do CRUD automaticamente:

``` php
    Route::resource('machines', MachinesController::class);
```
Esse comando cria as seguintes rotas:

|Método|URI|Ação|Nome da Rota|
|---|---|---|---|
|GET|/machines|index|machines.index|
|GET|/machines/create|create|machines.create|
|POST|/machines/store|store|machines.store|
|GET|/machines/{machine}|show|machines.show|
|GET|/machines/{machine}/edit|edit|machines.edit|
|PUT/PATCH|/machines/{machine}|update|machines.update|
|DELETE|/machines/{machine}|destroy|machines.destroy|

# 🧪 Como Executar o Projeto
### Clone este repositório:
``` bash
git clone [https://github.com/seu-usuario/seu-repo.git](https://github.com/Pedro-R1beiro/laravel-crud-basico.git)
```

### Acesse o diretório:
``` bash
cd nome-do-projeto
```

### Instale as dependências:
``` bash
composer install
```

### Copie o arquivo .env.example para .env:
``` bash
cp .env.example .env
```

### Configure seu banco de dados no .env

### Rode as migrations e seeders:
``` bash
php artisan migrate --seed
```

### Inicie o servidor local:

``` bash
php artisan serve
```

# 👤 Autor
>Pedro Ribeiro - [@Pedro-R1beiro](https://github.com/Pedro-R1beiro)
