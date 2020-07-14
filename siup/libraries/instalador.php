<?php
############ DS DIGITAL ###################

class Instalador
{
	################################################################################################################
	public function Instalar()
	{
		self::CriarSQl();
        $opcao = Componente::GetLibrary('opcao');
        $lista = $opcao->GetOpcoes();
        if($lista)
        {
			$opcao->SalvarOpcao("statussiup", "ativado");
            foreach ($lista as $key=>$item)
            {
				$valor = $opcao->GetOpcao($item['nome'], "");
				if(!empty($valor))
					continue;
			    Componente::SalvarOpcao($item['nome'], $item['default']);
			}
        }
		
		return;
	}
	################################################################################################################
	public function Desativar()
	{	
		$opcao = Componente::GetLibrary('opcao');
		$opcao->SalvarOpcao("statussiup", "desativado");
		return;
	}
	################################################################################################################
	public function Uninstall()
	{
		self::DeletarSQl();
		$opcao = Componente::GetInstancia('opcao');
		$lista = $opcao->ListaAtributo();
		if($lista)
        {
            foreach ($lista as $item)
                delete_option($item);
        }
		return;
	}
	################################################################################################################
	public static function CriarSQl()
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		$sqls = false;
		$opcao = Componente::GetLibrary('opcao');
		$ativo = $opcao->GetOpcao("statussiup", "");
		if(!empty($ativo))
			return;
		#region Lista de tabela no plugin
		$sqls[] = "DROP TABLE IF EXISTS `{$prefix}area`;";
		
		$sqls[] = "CREATE TABLE `{$prefix}area` (
			`idarea` INT(11) NOT NULL AUTO_INCREMENT,
			`idpai` INT(11) NOT NULL DEFAULT '0',
			`nome` VARCHAR(255) NULL DEFAULT NULL,
			`icone` VARCHAR(255) NULL DEFAULT NULL,
			`imagem` VARCHAR(255) NULL DEFAULT NULL,
			PRIMARY KEY (`idarea`)
		)
		COLLATE='latin1_swedish_ci'
		ENGINE=InnoDB
		;";

		$sqls[] = "DROP TABLE IF EXISTS `{$prefix}cliente`;";
				
		$sqls[] = "CREATE TABLE `{$prefix}cliente` (
			`idcliente` INT(11) NOT NULL AUTO_INCREMENT,
			`idsiup` INT(11) NOT NULL DEFAULT '0',
			`nome` VARCHAR(255) NOT NULL,
			`mae` VARCHAR(255) NULL DEFAULT NULL,
			`email` VARCHAR(255) NULL DEFAULT NULL,
			`senha` VARCHAR(12) NULL DEFAULT NULL,
			`datanascimento` DATE NULL DEFAULT NULL,
			`telefone` VARCHAR(50) NULL DEFAULT NULL,
			PRIMARY KEY (`idcliente`)
		)
		COLLATE='latin1_swedish_ci'
		ENGINE=InnoDB
		;";

		$sqls[] = "DROP TABLE IF EXISTS `{$prefix}documento`;";
				
		$sqls[] = "CREATE TABLE `{$prefix}documento` (
			`iddocumento` INT(11) NOT NULL AUTO_INCREMENT,
			`idpedido` INT(11) NOT NULL DEFAULT '0',
			`documento` VARCHAR(255) NULL DEFAULT NULL,
			`data` DATETIME NULL DEFAULT NULL,
			PRIMARY KEY (`iddocumento`)
		)
		COLLATE='latin1_swedish_ci'
		ENGINE=InnoDB
		;";

		$sqls[] = "DROP TABLE IF EXISTS `{$prefix}enquete`;";
				
		$sqls[] = "CREATE TABLE `{$prefix}enquete` (
			`idenquete` INT(11) NOT NULL AUTO_INCREMENT,
			`iduser` INT(11) NOT NULL DEFAULT '0',
			`pergunta` TEXT NOT NULL,
			`imagem` VARCHAR(255) NULL DEFAULT NULL,
			`status` ENUM('Ativo','Inativo') NOT NULL DEFAULT 'Ativo',
			`datainicio` DATE NULL DEFAULT NULL,
			`datafim` DATE NULL DEFAULT NULL,
			`ip` VARCHAR(50) NULL DEFAULT NULL,
			`cadastradoem` DATETIME NULL DEFAULT NULL,
			PRIMARY KEY (`idenquete`)
		)
		COLLATE='latin1_swedish_ci'
		ENGINE=InnoDB
		;";

		$sqls[] = "DROP TABLE IF EXISTS `{$prefix}equipe`;";
				
		$sqls[] = "CREATE TABLE `{$prefix}equipe` (
			`idequipe` INT(11) NOT NULL AUTO_INCREMENT,
			`iduser` INT(11) NOT NULL DEFAULT '0',
			`Nome` VARCHAR(255) NOT NULL,
			`cargo` VARCHAR(255) NOT NULL,
			`descricao` TEXT NULL,
			`foto` VARCHAR(255) NULL DEFAULT NULL,
			`status` ENUM('Ativo','Inativo') NOT NULL DEFAULT 'Ativo',
			`ip` VARCHAR(50) NULL DEFAULT NULL,
			`cadastradoem` DATETIME NULL DEFAULT NULL,
			PRIMARY KEY (`idequipe`)
		)
		COLLATE='latin1_swedish_ci'
		ENGINE=InnoDB
		;";

		$sqls[] = "DROP TABLE IF EXISTS `{$prefix}evento`;";
				
		$sqls[] = "CREATE TABLE `{$prefix}evento` (
			`idevento` INT(11) NOT NULL AUTO_INCREMENT,
			`iduser` INT(11) NULL DEFAULT NULL,
			`titulo` VARCHAR(255) NULL DEFAULT NULL,
			`resumo` TEXT NULL,
			`descricao` TEXT NULL,
			`datainicio` DATETIME NULL DEFAULT NULL,
			`datafim` DATETIME NULL DEFAULT NULL,
			`imagem` VARCHAR(255) NULL DEFAULT NULL,
			`thumbnail` VARCHAR(255) NULL DEFAULT NULL,
			`status` ENUM('Aguardando Publicação','Publicado','Realizado','Cancelado') NOT NULL DEFAULT 'Aguardando Publicação',
			`ip` VARCHAR(50) NULL DEFAULT NULL,
			`cadastradoem` DATETIME NULL DEFAULT NULL,
			PRIMARY KEY (`idevento`)
		)
		COLLATE='latin1_swedish_ci'
		ENGINE=InnoDB
		;";

		$sqls[] = "DROP TABLE IF EXISTS `{$prefix}midiassocial`;";
				
		$sqls[] = "CREATE TABLE `{$prefix}midiassocial` (
			`idmidiassocial` INT(11) NOT NULL AUTO_INCREMENT,
			`iduser` INT(11) NOT NULL DEFAULT '0',
			`nome` VARCHAR(255) NULL DEFAULT NULL,
			`imagem` VARCHAR(255) NULL DEFAULT NULL,
			`icone` VARCHAR(255) NULL DEFAULT NULL,
			`link` VARCHAR(255) NULL DEFAULT NULL,
			`status` ENUM('Ativo','Inativo') NOT NULL DEFAULT 'Ativo',
			`ip` VARCHAR(255) NULL DEFAULT NULL,
			`cadastradoem` DATETIME NULL DEFAULT NULL,
			PRIMARY KEY (`idmidiassocial`)
		)
		COLLATE='latin1_swedish_ci'
		ENGINE=InnoDB;";

		$sqls[] = "DROP TABLE IF EXISTS `{$prefix}opcaoenquete`;";
				
		$sqls[] = "CREATE TABLE `{$prefix}opcaoenquete` (
			`idopcaoenquete` INT(11) NOT NULL AUTO_INCREMENT,
			`idenquete` INT(11) NOT NULL DEFAULT '0',
			`opcao` TEXT NULL,
			`votos` INT(11) NOT NULL DEFAULT '0',
			PRIMARY KEY (`idopcaoenquete`)
		)
		COLLATE='latin1_swedish_ci'
		ENGINE=InnoDB;";

		$sqls[] = "DROP TABLE IF EXISTS `{$prefix}pedido`;";
				
		$sqls[] = "CREATE TABLE `{$prefix}pedido` (
			`idpedido` INT(11) NOT NULL AUTO_INCREMENT,
			`idtarefa` INT(11) NOT NULL DEFAULT '0',
			`idcliente` INT(11) NOT NULL DEFAULT '0',
			`idarea` INT(11) NOT NULL DEFAULT '0',
			`descricao` TEXT NULL,
			`status` ENUM('Aguardando Atendimento','Em  Atendimento','Finalizado','Cancelado') NOT NULL DEFAULT 'Aguardando Atendimento',
			`ip` VARCHAR(50) NULL DEFAULT NULL,
			`datapedido` DATETIME NULL DEFAULT NULL,
			PRIMARY KEY (`idpedido`)
		)
		COLLATE='latin1_swedish_ci'
		ENGINE=InnoDB;";
		#endregion
		
		
		if(is_array($sqls))
		{
			foreach ($sqls as $key => $sql)
			{
				$wpdb->query($sql);
			}
		}
		return;
	}
	################################################################################################################
	public static function DeletarSQl()
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		$sqls = false;

		#region Exemplo de deleção de tabela no plugin
		$sqls[] = "DROP TABLE IF EXISTS `{$prefix}area`;";
		$sqls[] = "DROP TABLE IF EXISTS `{$prefix}cliente`;";
		$sqls[] = "DROP TABLE IF EXISTS `{$prefix}documento`;";
		$sqls[] = "DROP TABLE IF EXISTS `{$prefix}enquete`;";
		$sqls[] = "DROP TABLE IF EXISTS `{$prefix}equipe`;";
		$sqls[] = "DROP TABLE IF EXISTS `{$prefix}evento`;";
		$sqls[] = "DROP TABLE IF EXISTS `{$prefix}midiassocial`;";
		$sqls[] = "DROP TABLE IF EXISTS `{$prefix}opcaoenquete`;";
		$sqls[] = "DROP TABLE IF EXISTS `{$prefix}pedido`;";
		#endregion
		if(is_array($sqls))
		{
			foreach ($sqls as $key => $sql)
			{
				$wpdb->query($sql);
			}
		}
		return;
	}
}