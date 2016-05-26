# Pojeto Onça Pintada
Este projeto foi desenvolvido como parte das ativades da Jungle Party 2016 da Estácio de Sá - FAP

### Instalação
Para rodar a aplicação, é necessário ter o servidor HTTP Apache 2 instalado e configurado com os módulos de `rewrite`
bem como o PHP versão >= 5.3

Para habilitar o módulo no Apache basta esta linha:
```bash
      sudo a2enmod rewrite
```

Agora abra o arquivo de configuração:
```bash
      sudo gedit  /etc/apache2/sites-available/default
```

Procure no seu arquivo a entrada **AllowOverride None**.

Altere esse valor para **AllowOverride All** .
Salve o arquivo e reinicie o Apache.
```bash
      sudo /etc/init.d/apache2 restart
```

Para que a aplicação rode normalmente, recomendamdos que o servidor aponte para o diretório `public_html`.
Logo após estes procedimentos, é preciso alterar o domínio padrão usado através do arquivo localisado em
`App/Config/Config.conf.php`:
```php
      // código omitido
      // alterar 'localhos' para o domínio/sub-domínio padrão
      define('DOMAIN', 'localhost');
      // código omitido
```
Também é preciso alterar as configurações de acesso ao Bando de Dados (MySQL), para tal
use a ferramenta `assist` do próprio framework. Abra o terminal e digite conforme abaixo, aleterando pelas informações da sua conexão MySQL:
```bash
      $ php assist configdb SERVER DATABASE USER PASS
```


### Usando a aplicação
Esta aplicação é apenas para fins acadêmicos, contudo, por se tratar de um projeto Open Source
pode ser usada em produção.

#### Dados de Acesso (login)
User: admin

pass: admin123

### Requisitos
Server Apache 2 >= V2.*

PHP5 >= V5.3 (Recomendado >= 5.5)

MySQL 5.5

### Créditos
Esta aplicação foi orgulhosamente desenvolvida em uma distribuição Linux por:

Bruno Monteiro - bruno.monteirodg@gmail.com

Orientado pelo Professor Antonio Lobato - alobato@gmail.com

