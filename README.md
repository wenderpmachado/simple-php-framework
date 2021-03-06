## Simples framework em PHP ##

<!--### História ###-->
<!-------------------->
<!--Após um bom tempo estudando o [Laravel Framework](https://github.com/laravel/laravel) (PHP) e um pouco de [Rails](https://github.com/rails/rails) (Ruby), de várias pesquisas em relação a quais tecnologias utilizar para projetos em que participo e -->
<!--de conversas com amigos de trabalho e faculdade, incluindo professores¹, resolvi reunir o que achava interessante, framework agnóstico e *clean*, junto com algumas ideias vistas no -->
<!--Laravel/Rails, com o intuito de praticar todo conhecimento adiquirido e utilizar em projetos próprios que não necessitam de grande infraestrutura.-->

<!--Vale ressaltar que **não** é aconselhavel a utilização deste framework em produção, ainda. :)-->

<!--¹: *Agradecimento especial ao professor [Thiago Delgado Pinto](https://github.com/thiagodp) pelas diversas explicações e ótimas bibliotecas disponibilizadas aqui no github.*-->

### Bibliotecas ###
-------------------
- robmorgan/phinx
- phputil/json
- phputil/pdowrapper
- phputil/di
- phputil/rtti
- phputil/traits
- vlucas/phpdotenv
- slim/slim

### Arquitetura ###
-------------------
O framework implementa o modelo MVC baseado no [Repository Pattern](https://github.com/domnikl/DesignPatternsPHP/tree/master/More/Repository).
Cada modelo possui uma pasta com seu nome dentro da pasta principal do projeto, ```app/```, tendo as seguintes classes:

- ModelName
    - Classe referente ao objeto de domínio
    
- ModelNameRepository
    - Interface desse modelo, contendo funções específicas
    - Extende da DefaultRepository, própria do framework, com as funções padrões do CRUD
    
- ModelNameRBRepository
    -   Classe com as regras de negócio referente ao Banco de Dados Relacional
    -   Extende da DefaultRDRepository, própria do framework, com as funções padrões do CRUD já implementadas
    
### Criação das Classes ###
---------------------------
Para fins de criar essa estrutura, foi criado uma classe chamada ```ClassMaker```, na pasta ```app/helper```, que deve ser utilizada em um arquivo temporário².
No arquivo temporário seria possível utilizar os métodos:

- makeModel
- makeRepository
- makeController³
    
Exemplo:

```php
<?php

require_once '../vendor/autoload.php';

$classMaker = new ClassMaker();
$className = 'Address';
$parameters = [
    'id' => 'integer',
    'road' => 'string'
];
$classMaker->makeModel($className, $parameters);
$classMaker->makeRepository($className, $parameters);
```
 
²: *Futuramente será implementado a criação dessa estrutura de classes via CLI*

³: *Ainda não implementado*

### Relacionamento entre Classes ###
------------------------------------
Como é feito em um Object Relational Mapper (ORM), há possibilidade de relacionar classes de forma simples graças a padronização da arquitetura.
Essa associação é feita através de algumas funções implementadas na classe ```DefaultRDRepository``` (herdada por todas as novas RDRepository's) da interface ```Relationships```. Elas são:

- hasOne
- hasMany
- belongsTo
- belongsToMany

Imaginemos um cenário onde existem dois modelos: um chamado ```User``` e o outro ```Account```, onde *account* possui a chave estrangeira de *user*. 
*User* está apto a possuir *hasOne* ou *hasMany* e *Account* a possuir *belongsTo* ou *belongsToMany*, dependendo da quantidade permitida de contas por usuário.

Exemplo:

```php
<?php

namespace App\User;

use App\Database\DefaultRDRepository;

class UserRDRepository extends DefaultRDRepository implements UserRepository {
	...
	public function account(&user){
        return $this->hasOne($user, 'App\Account\Account');
    }
	...
}
```

Após essa breve configuração, é possível chamar através da ```UserRDRepository``` a função *account*, passando como argumento o usuário, sendo retornado a sua conta.

### Injeção de Dependência ###
-------------------------------
A injeção de dependência (ou inversão de dependência) é configurada no arquivo ```config/ioc.php```, utilizado a biblioteca [thiagodp/di](https://github.com/thiagodp/di).
Para facilitar esse procedimento, a ```ClassMaker``` faz esse trabalho pra gente logo após executar a função *makeRepository*, inserindo a linha de configuração da classe recém criada.

```php
...
DI::config(DI::let('AddressRepository')->create('\App\Address\AddressRDRepository')->shared());
```

Após realizar essa configuração, o comando *DI::make('AddressRepository')* retornará um objeto *AddressRDRepository*, pronto para realizar as chamadas ao banco de dados relacional.

### Migrações ###
-----------------
Após todas as configurações feitas, é necessário criar a tabela no banco de dados. O ClassMaker aproveita os parametros passados na criação das classes acima para criar uma migração
de criação de tabela, através da biblioteca [robmorgan/phinx](https://github.com/robmorgan/phinx/), que pode ser executada através do comando no console:
 
```cmd
$ vendor/bin/phinx migrate
```

Para cada alteração no banco de dados é indicado a criação de uma migração com a implementação das funções: *up* e *down*
Ao rodar o comando *migrate*, todas as migrações que ainda não foram rodadas serão nesse momento, utilizando a função *up*.
Caso deseja reverter as alterações, o comando a ser executado no console é:

```cmd
$ vendor/bin/phinx rollback
```
Neste caso a função *down* será executada.

Para mais detalhes sobre as migrações, acesse a wiki do projeto em: [docs.phinx.org](http://docs.phinx.org/en/latest/) 