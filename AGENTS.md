1. Sempre que realizada alterações em arquivos nas pastas db ou lang, é necessário incrementar o número da version e release em version.php. O version segue o padrão YYYY_MM_DD_XXX e o release segue o padrão 4.5.XXX. O XXX deve ser incrementado em 1 sempre que for realizada uma alteração em arquivos nas pastas db ou lang. O YYYY_MM_DD deve refletir a data em que a alteração foi realizada.

2. Sempre que alterar o código, ao final, valide se está funcionando usando `act -j test --matrix php:8.3 --matrix database:pgsql`.

3. O uso do **pre-commit** é **obrigatório** no repositório. O hook de pre-commit está configurado em `.pre-commit-config.yaml` e `.githooks/pre-commit` para forçar a execução dos testes via `act -j test --matrix php:8.3 --matrix database:pgsql` antes de qualquer commit.

4. antes de uma release conferir se a documentação está atualizada.

5. antes de um commit, garantir que o pre-commit está rodando corretamente. e que não existem string não internacionalizadas. e que todos os idiomas da internacionalização estão atualizados.
