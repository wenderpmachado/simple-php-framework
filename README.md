## Simples framework em PHP ##

### História ###
----------------
Após um bom tempo estudando o [Laravel Framework](https://github.com/laravel/laravel) (PHP) e um pouco de [Rails](https://github.com/rails/rails) (Ruby), de várias pesquisar em relação a quais tecnologias utilizar para projetos em que participo e 
de conversas com amigos de trabalho e faculdade, incluindo professores¹, resolvi reunir o que achava interessante, framework agnóstico e *clean*, junto com algumas ideias vistas no 
Laravel/Rails, com o intuito de utilizar em projetos próprios que não necessitam de grande infraestrutura. 

¹: *Agradecimento especial ao professor [Thiago Delgado Pinto](https://github.com/thiagodp) pelas diversas explicações e ótimas bibliotecas disponibilizadas aqui no github.*

### Arquitetura ###
-------------------
O framework implementa o CRUD baseado no [Repository Pattern](https://github.com/domnikl/DesignPatternsPHP/tree/master/More/Repository).
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
Para fins de criar essa estrutura, foi criado uma classe chamada ```ClassMaker```, na raiz da pasta ```app```, que deve ser utilizada em um arquivo temporário².
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
Essa associação é feita através de algumas funções implementadas da interface ```Relationships``` na classe ```DefaultRDRepository``` (herdada por todas as novas RDRepository's). Elas são:

- hasOne
- hasMany
- belongsTo
- belongsToMany

Imaginemos um cenário onde existam dois modelos: um chamado ```User``` e o outro ```Account```, onde *account* possui a chave estrangeira de *user*. 
*User* está apto a possuir *hasOne* ou *hasMany* e *Account* a possuir *belongsTo* ou *belongsToMany*, dependendo da quantidade permitida de contas por usuário.

Exemplo:

```php
<?php

namespace App\User;

use App\Database\DefaultRDRepository;

class UserRDRepository extends DefaultRDRepository implements UserRepository {
	...
	public function conta(&user){
        return $this->hasOne(user, 'App\Account\Account');
    }
	...
}
```