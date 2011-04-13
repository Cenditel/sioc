<?php
/**
 * This file is part of P4A - PHP For Applications.
 *
 * P4A is free software: you can redistribute it and/or modify it
 * under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of
 * the License, or (at your option) any later version.
 *
 * P4A is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with P4A.  If not, see <http://www.gnu.org/licenses/lgpl.html>.
 *
 * To contact the authors write to:                                     <br />
 * CreaLabs SNC                                                         <br />
 * Via Medail, 32                                                       <br />
 * 10144 Torino (Italy)                                                 <br />
 * Website: {@link http://www.crealabs.it}                              <br />
 * E-mail: {@link mailto:info@crealabs.it info@crealabs.it}
 *
 * @author Andrea Giardina <andrea.giardina@crealabs.it>
 * @author Fabrizio Balliano <fabrizio.balliano@crealabs.it>
 * @copyright CreaLabs SNC
 * @link http://www.crealabs.it
 * @link http://p4a.sourceforge.net
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package p4a
 */

/**
 * *********Traducción No Oficial *********
 * Este archivo es parte de P4A - PHP Para Aplicaciones
 *
 * P4A es Software Libre: usted puede redistribuirlo y/o modificarlo
 * bajo los términos de la GNU Lesser Licencia Pública General Menor (Lesser General Public License) tal y como está
 * publicada por la Fundación de Software Libre, en la versión 3 de
 * la Lincecia, o (a su elección) alguna versión posterior.
 *
 * P4A es distribuido con la esperanza de que pueda serle útil,
 * pero SIN NINGUNA GARANTÍA; sin siquiera la garantía implicita de
 * COMERCIABILIDAD o IDIONIDAD PARA UN PROPÓSITO PARTICULAR. Vea la
 * GNU Licencia Pública General Menor (Lesser General Public License) para más detalles.
 *
 * Usted debería tener una copia de la GNU Licencia Pública General Menor (GNU Lesser General Public License)
 * junto con P4A.  Sí no, vea <http://www.gnu.org/licenses/lgpl.html>.
 *
 * Para contactar a los autores escriba a:                              <br />
 * CreaLabs SNC                                                         <br />
 * Via Medail, 32                                                       <br />
 * 10144 Torino (Italy)                                                 <br />
 * Website: {@link http://www.crealabs.it}                              <br />
 * E-mail: {@link mailto:info@crealabs.it info@crealabs.it}
 *
 * @author Andrea Giardina <andrea.giardina@crealabs.it>
 * @author Fabrizio Balliano <fabrizio.balliano@crealabs.it>
 * @copyright CreaLabs SNC
 * @link http://www.crealabs.it
 * @link http://p4a.sourceforge.net
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package p4a
 */

/**
 * Sistema de Información para la Organización Comunitaria
 * Desarrollado por
 * Software:
 * @author Cesar Carbonara <cesar.carbonara@gmail.com>
 * Documentos:
 * @author Leonardo Lobo <leonardolelectiva@gmail.com>
 * Asesoría y apoyo:
 * @author Leonardo Caballero <leonardocaballero@gmail.com>
 * Durante le proceso académico para la obtención del título
 * Ingeniero en Informática
 * Programa Nacional de Formación en Informática
 * Instituto Universitario Tecnológico de Ejido
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @copyleft 2010
 *
 * @package SIOC
 */
class Base extends P4A
{
    public function __construct()
    {
        parent::__construct();
        // definir el título en la barra del navegador
        $this->setTitle("Apoya Comunidad");

		$this->build("p4a_db_source","source_censo")
			->setTable("censo")
			->load();

        // abrir la máscara para iniciar sesión
        $this->openMask("login");
        // mostrar mensaje
        $this->loginInfo();
    }
    public function construirMenu()
    {
        // destruir el objeto para ahorrar memoria y vacia la cache
        if ( isset($this->menu) AND is_object($this->menu)) {
            $this->menu->destroy();
        }
        // definir el objeto
        $this->build("p4a_menu","menu");

        // agregar elementos dinámicamente
        // extraer elementos de la base de datos y agregarlos al menú
        $queryMenu = "SELECT etiqueta, mascara, id_padre, etiqueta_padre, mascara_padre, visible, accion
        FROM cc1.menu
        JOIN cc1.usuario_menu ON menu.id = usuario_menu.id_menu
        WHERE usuario_menu.id_usuario = {$_SESSION['id']} AND menu.activo = true
        ORDER BY posicion, etiqueta";
/*
        $this->messageWarning("sql: $queryMenu");//return;
*/
        $db = P4A_DB::singleton();
        $items = $db->fetchAll($queryMenu);
        foreach ($items as $item) {
        	$id_padre = $item["id_padre"];
			$etiqueta = $item["etiqueta"];
			$etiqueta_padre = $item["etiqueta_padre"];
			$mascara = $item["mascara"];
			$mascara_padre = $item["mascara_padre"];

			if (!$mascara_padre) {
				$item_obj = $this->menu;
			} else {
				$item_obj = $this->menu->items->$mascara_padre;
			}

			$item_obj->addItem($mascara);
			$item_obj->items->$mascara->setLabel($etiqueta);
			$item_obj->items->$mascara->setVisible($item['visible']);

			if ($item['accion'])
				$item_obj->items->$mascara->implement("onClick", $this, $item['accion']);
		}

    }
    public function menuClick()
    {
        /**
         * Función que abre la máscara cuando se selecciona una opción del menú
         */
        $this->active_mask->destroy();
        $this->openMask($this->active_object->getName());
    }
    protected function loginInfo()
    {
        /**
         * Función con el mensaje de entrada al sistema
         *
         * ** se debe buscar un mensaje más descriptivo **
         */
        $this->messageInfo("Bienvenid@ al Sistema");
        $this->messageInfo("Ingrese Usuario y Contraseña");
    }
}
?>
