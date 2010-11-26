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
class login extends p4a_base_mask
{
    public function __construct()
    {
        parent::__construct();

        $this->setTitle("Bienvenido al Sistema");

        // objetos del formulario
        $this->crearObjetos();

        $frm =& $this->build('p4a_frame','frm');
        $frm->setWidth(400);

        $frm->anchorCenter($this->txtLogin);
        $frm->anchorCenter($this->txtPassword);
        $frm->anchorCenter($this->btnAutenticar);

        $this->btnAutenticar->addAction("onClick");
        $this->intercept($this->btnAutenticar, 'onClick', 'autenticar');

        $this->display("main", $frm)->setFocus($this->txtLogin);

    }
    public function crearObjetos()
    {
        $this->build("p4a_field","txtLogin");
        $this->build("p4a_field","txtPassword");
        $this->build("p4a_button","btnAutenticar");

        $this->propiedadesObjetos();

        return;
    }
    public function propiedadesObjetos()
    {
        $this->txtLogin
            ->setLabel("Usuario")
            ->implement("onreturnpress", $this, "autenticar");

        $this->txtPassword
            ->setLabel("Contraseña")
            ->setType("password")
            ->setEncryptionType('md5')
            ->implement("onreturnpress", $this, "autenticar");

        $this->btnAutenticar
            ->setLabel("Entrar al Sistema");
    }

    public function autenticar()
    {
        /*
         * Autenticar contra la Base de Datos el usuario que se loguea
         */
        $username = $this->txtLogin->getNewValue();
        $password = $this->txtPassword->getNewValue();

/*
		if ($username === 'admin')
		{
			$this->Info("Usuario Administrador");
			return;
		}

        if ($username == 'p4a')
        {
        	$this->warning("Usuario con permisos restringidos");
        	return;
		}
*/

        $db = P4A_DB::singleton();
        $this->user_data = $db->fetchRow("SELECT * FROM cc1.usuarios WHERE usuario = '$username' AND clave = '$password'");

        if ($this->user_data['nivel_acceso'] == '0')
        {
            $this->warning("Usted está inhabilitado(a) para ingresar al sistema temporalmente");
            return;
        }

        if (!$this->user_data) {
            $this->warning("Nombre de Usuario o Contraseña errado!");
        } else {
            P4A::singleton();
            $_SESSION["id"] = $this->user_data['id'];
            $_SESSION["usuario"] = $this->user_data['usuario'];

            $this->info("Bienvenid@ al Sistema ".$this->user_data['usuario']);

            $p4a =& p4a::singleton();
            $p4a->construirMenu();
            $p4a->openMask($this->user_data['default_mask']);
        }
    }

    // función para la primera corrida
    public function login()
    {
        $username = $this->txtLogin->getNewValue();
        $password = $this->txtPassword->getNewValue();
/*
        $username = $this->active_mask->username->getNewValue();
        $password = $this->active_mask->password->getNewValue();
*/
        $p4a =& p4a::singleton();
        if ($username == "p4a" and $password == md5("p4a")) {
            $p4a->messageInfo("Login successful");
            $p4a->openMask("crearUsuarios");
        } else {
            $p4a->messageError("Login failed");
            $p4a->loginInfo();
        }
    }
}

