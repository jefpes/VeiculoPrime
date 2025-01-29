## Sobre Motor Market

Motor Market é uma aplicação voltada para lojistas do ramo automobilístico de pequeno e médio porte, permitindo a digitalização de seus negócios. Com o Motor Market, os lojistas podem gerenciar suas finanças, exibir seu estoque de veículos por meio de um site personalizado e participar de um marketplace de veículos.

## Tecnologias e plugins

##### Tecnologias

-   Laravel 11: Framework PHP robusto e moderno.
-   Livewire 3: Ferramenta para criar componentes reativos em Laravel.
-   Filament V3: Framework para construir painéis administrativos com Laravel.

##### Plugins

-   Brazilian Form Fields ( https://github.com/leandrocfe/filament-ptbr-form-fields )
-   Tenancy for Laravel ( https://tenancyforlaravel.com )
-   Swiperjs ( https://swiperjs.com )

## Estrutura

A aplicação utiliza uma estrutura multi-tenancy com o pacote Tenancy for Laravel, permitindo que:

-   Cada tenant seja isolado com seu próprio banco de dados.
-   Cada tenant gerencie várias lojas.
-   O painel master (administrador principal) seja responsável por criar e gerenciar os tenants.

## Instalacao

#### Composer and NPM

```bash
composer install && npm install
```

#### ENV

```bash
 cp .env.example .env
```

#### Key

```bash
php artisan key:generate
```

#### Link Storage

```bash
php artisan storage:link
```

#### Migrações e seed

###### Painel Master ( /master )

```bash
php artisan migrate:fresh --seeder=LandlordSeeder
```

user: `master@admin.com`
password: `admin`

###### Painel Tenant ( /admin )

Crie um tenant no painel master ( /master )

quando registrar o email e a senha serao suas credenciais, para ter um banco populado ( testes ), apos a criacao do tenant

```bash
php artisan tenants:migrate-fresh && php artisan tenants:seed
```

user: `master@admin.com`
password: `admin`

#### Copiar as fotos para exemplo

```bash
cp -r photos_exemplo storage/app/public/ && mv ./storage/app/public/photos_exemplo ./storage/app/public/photos
```
