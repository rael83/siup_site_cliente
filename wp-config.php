<?php
/**
 * As configurações básicas do WordPress
 *
 * O script de criação wp-config.php usa esse arquivo durante a instalação.
 * Você não precisa usar o site, você pode copiar este arquivo
 * para "wp-config.php" e preencher os valores.
 *
 * Este arquivo contém as seguintes configurações:
 *
 * * Configurações do MySQL
 * * Chaves secretas
 * * Prefixo do banco de dados
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Configurações do MySQL - Você pode pegar estas informações com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define( 'DB_NAME', 'site_siup' );

/** Usuário do banco de dados MySQL */
define( 'DB_USER', 'root' );

/** Senha do banco de dados MySQL */
define( 'DB_PASSWORD', '' );

/** Nome do host do MySQL */
define( 'DB_HOST', 'localhost' );

/** Charset do banco de dados a ser usado na criação das tabelas. */
define( 'DB_CHARSET', 'utf8mb4' );

/** O tipo de Collate do banco de dados. Não altere isso se tiver dúvidas. */
define( 'DB_COLLATE', '' );

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las
 * usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org
 * secret-key service}
 * Você pode alterá-las a qualquer momento para invalidar quaisquer
 * cookies existentes. Isto irá forçar todos os
 * usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'TI(ApmG{1HKb+;>*`GOG,2 z|^2Sv?cpS}|,zOE2L5.c{zMB4#!(lRk 9}J}I^K)' );
define( 'SECURE_AUTH_KEY',  'g4_:unIht)<Lt_jc=hT]@czcwi7,m-v47dAw&|lJBONa?^-rlMtP~% ~#w?OzrOF' );
define( 'LOGGED_IN_KEY',    'c-T^N+kK6?6G+d^/w4q- FB]+;Cb]]w{RWr`MEbr]Sb@Nbw0:(33pB&q/Boe_Oq(' );
define( 'NONCE_KEY',        'Ok>xR`x-Thv9_5mTRlSsq@[{bMOR>rW^F^_Inh~(Il:+7ZPL9]K;vKlkxO5 V4[Q' );
define( 'AUTH_SALT',        'bMoW`uW&JCMdHefS>D>`G9P{3bK[N$|VjXD}]urBt./ciH2Z~FMm)X38ND_,J)M`' );
define( 'SECURE_AUTH_SALT', 'v!MSIm%iFl+{&QHX44&vP6{G8|$?-T`=$ihLPGy]PfTI&r_o~^Ez6hQN-&*g-|D[' );
define( 'LOGGED_IN_SALT',   '1]E+)Q6wpV^[1`{AE3z_J5_[KErrrdF_4IwvJSxBVjf_,~%<yC[htqhyQafA`gNe' );
define( 'NONCE_SALT',       'BFXoI&vCOI{4g H$-qB|Aj:%}m`k2)PYRWZUkzD1|ds)cZY9mdvno2oD2}rKfG_t' );

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der
 * um prefixo único para cada um. Somente números, letras e sublinhados!
 */
$table_prefix = 'wp_';

/**
 * Para desenvolvedores: Modo de debug do WordPress.
 *
 * Altere isto para true para ativar a exibição de avisos
 * durante o desenvolvimento. É altamente recomendável que os
 * desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 *
 * Para informações sobre outras constantes que podem ser utilizadas
 * para depuração, visite o Codex.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Configura as variáveis e arquivos do WordPress. */
require_once ABSPATH . 'wp-settings.php';
