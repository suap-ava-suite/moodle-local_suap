# Política de Segurança

## Versões com Suporte

Apenas a versão mais recente do plugin recebe correções de segurança.

| Versão | Com suporte       |
|--------|-------------------|
| 4.5.x  | ✅ Sim (atual)    |
| < 4.5  | ❌ Não            |

## Relatando uma Vulnerabilidade

Se você descobriu uma vulnerabilidade de segurança neste plugin, **não abra uma issue pública**. Siga as etapas abaixo:

1. **Envie um e-mail** para o mantenedor do projeto, Kelson Medeiros (`kelsoncm@gmail.com`), descrevendo o problema, ou crie diretamente um [Private Vulnerability Report](https://github.com/suap-ava-suite/moodle-local_suap/security/advisories/new) pelo GitHub:
   - Assunto: `[SECURITY] moodle-local_suap – <resumo breve>`
   - Descrição detalhada da vulnerabilidade
   - Passos para reprodução ou prova de conceito (PoC)
   - Impacto potencial ou vetores de exploração
   - Versão do plugin e do Moodle afetadas
   - (Opcional) Sugestão de correção

2. **Aguarde a confirmação.** Você receberá um retorno em até **5 dias úteis** confirmando o recebimento e indicando os próximos passos.

3. **Processo de correção.** Após a confirmação, trabalharemos em conjunto para validar, corrigir e divulgar a vulnerabilidade de forma responsável. O prazo-alvo para disponibilizar uma correção é de **30 dias** após a confirmação.

4. **Divulgação coordenada.** A vulnerabilidade será divulgada publicamente somente após a publicação de uma versão corrigida, salvo acordo diferente com o pesquisador.

## Escopo

Este projeto é o plugin **núcleo de integração** entre o Moodle e o SUAP Edu, recebendo via API HTTP própria (`api/`) estruturas de curso, matrícula, coorte e nota vindas do SUAP (tipicamente através do Integrador AVA) e aplicando-as no Moodle. As vulnerabilidades de interesse incluem, mas não se limitam a:

- Falhas na autenticação por token dos endpoints de `api/` (`servicelib.php`) que permitam acesso não autorizado aos serviços de sincronização
- Vazamento, manipulação ou exposição indevida do `auth_token` usado na autenticação da API
- Falhas no serviço `sync_up_enrolments` que permitam criação, alteração ou exclusão indevida de categorias, cursos, usuários, coortes, inscrições (matrículas) ou papéis (roles)
- Exposição indevida de dados pessoais ou acadêmicos sincronizados do SUAP (dados de usuários, notas, matrículas, campos de perfil customizados) através de qualquer endpoint da API ou das telas administrativas (`admin/`)
- Escalada de privilégios ou burla das capacidades `local/suap:adminview` e `local/suap:view_mooc_reports`
- Falhas nos serviços de sincronização em sentido inverso (`sync_down_grades`, `sync_user_preference`, `set_user_preference`) que permitam leitura ou gravação indevida de notas e preferências de usuário
- Injeção SQL ou execução remota de código no contexto do plugin
- Cross-Site Scripting (XSS) ou Cross-Site Request Forgery (CSRF) introduzidos pelo plugin

Vulnerabilidades no **Moodle core** ou em outros plugins da suíte (como `auth_suap`) devem ser reportadas diretamente ao [programa de segurança do Moodle](https://moodle.org/security/) ou ao repositório correspondente.

## Boas Práticas para Quem Usa o Plugin

- Mantenha o plugin sempre atualizado para a versão mais recente.
- Mantenha o Moodle atualizado, aplicando todos os patches de segurança oficiais.
- Gere um `auth_token` forte e o mantenha fora do código-fonte, nunca o exponha em logs, repositórios ou payloads de erro.
- Restrinja o acesso aos endpoints de `api/` a integrações confiáveis (Integrador AVA, Painel AVA) sempre que possível, por exemplo via firewall ou lista de IPs permitidos.
- Conceda as capacidades `local/suap:adminview` e `local/suap:view_mooc_reports` apenas aos papéis estritamente necessários.
- Revise periodicamente as solicitações de sincronização registradas nas telas administrativas do plugin em busca de atividade anômala.

## Créditos

Agradecemos a todos que contribuem para a segurança deste projeto de forma responsável.

---

© 2026 Kelson da Costa Medeiros – Licença [GNU GPL v3 ou superior](http://www.gnu.org/copyleft/gpl.html)
