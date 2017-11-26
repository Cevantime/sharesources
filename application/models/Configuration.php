<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of configuration
 *
 * @author thibault
 */
class Configuration extends DATA_Model
{

    private $_configDatas;

    public function getTableName()
    {
        return 'configurations';
    }

    public function getPrimaryColumns()
    {
        return array('key');
    }

    private function initConfigDatas()
    {
        $datas = $this->getList();
        $this->_configDatas = array();
        if ($datas) {
            foreach ($datas as $value) {
                $this->_configDatas[$value->key] = $value->value;
            }
        }
        return $this->_configDatas;
    }

    private function getConfigDatas()
    {
        if (!$this->_configDatas) {
            $this->initConfigDatas();
        }
        return $this->_configDatas;
    }

    public function getValues($cols = null)
    {
        $values = $this->getConfigDatas();
        if ($cols) {
            $wantedValues = array();
            if (!is_array($cols)) {
                $cols = array($cols);
            }
            foreach ($cols as $key => $value) {
                if (is_int($value)) {
                    $wantedValues[$value] = isset($values[$value]) ? $values[$value] : null;
                } else {
                    $wantedValues[$key] = isset($values[$key]) ? $values[$key] : $value;
                }
            }
            return $wantedValues;
        }
        return $values;
    }

    public function setValues($values)
    {
        $group = array();
        foreach ($values as $key => $value) {
            if (is_int($key) && is_array($value) && isset($value['key']) && isset($value['value'])) {
                $group[] = $value;
            } else if (is_string($key) && is_string($value)) {
                $group[] = array('key' => $key, 'value' => $value);
            }
        }
        $this->updateGroup($group);
    }

    public function setValue($key, $value, $description = null)
    {
        $saved = array('key' => $key, 'value' => $value);
        if ($description) {
            $saved['description'] = $description;
        }
        $this->save($saved);
    }

    public function getValue($key, $default = null)
    {
        $configDatas = $this->getConfigDatas();

        return isset($configDatas[$key]) ? $configDatas[$key] : ($default ? $default : false);
    }

    public function validationRulesForInsert($datas)
    {
        return array(
            'app_title' => array(
                'field' => 'app_title',
                'label' => translate('Titre de l\'application'),
                'rules' => 'min_length[2]|max_length[30]'
            ),
            'app_title_small' => array(
                'field' => 'app_title_small',
                'label' => translate('Titre raccourci'),
                'rules' => 'min_length[1]|max_length[2]'
            ),
            'app_login_image' => array(
                'field' => 'app_login_image',
                'label' => translate('Fond d\'écran'),
                'rules' => 'file_allowed_type[image]|file_size_max[700KB]'
            ),
            'app_sidebar_image' => array(
                'field' => 'app_sidebar_image',
                'label' => translate('Petite image'),
                'rules' => 'file_allowed_type[image]|file_size_max[100KB]'
            ),
            'signature_for_mailing' => array(
                'field' => 'signature_for_mailing',
                'label' => translate('Signature des emails'),
                'rules' => 'min_length[2]|max_length[50]'
            ),
            'email_for_mailing' => array(
                'field' => 'email_for_mailing',
                'label' => translate('Email de provenance'),
                'rules' => 'valid_email'
            ),
            'name_for_mailing' => array(
                'field' => 'name_for_mailing',
                'label' => translate('Nom de l\'émetteur des émails'),
                'rules' => 'min_length[2]|max_length[30]'
            ),
            'host_smtp' => array(
                'field' => 'host_smtp',
                'label' => translate('Hôte smtp'),
                'rules' => 'valid_url'
            ),
            'email_smtp' => array(
                'field' => 'email_smtp',
                'label' => translate('Email smtp'),
                'rules' => 'valid_email'
            ),
            'password_smtp' => array(
                'field' => 'password_smtp',
                'label' => translate('Mot de passe smtp'),
                'rules' => ''
            ),
            'color1' => array(
                'field' => 'color1',
                'label' => translate('Couleur'),
                'rules' => 'regex_match[/^([A-Fa-f0-9]{6})$/i]'
            ),
            'color2' => array(
                'field' => 'color2',
                'label' => translate('Couleur'),
                'rules' => 'regex_match[/^([A-Fa-f0-9]{6})$/i]'
            )
        );
    }

    public function validationRulesForUpdate($datas)
    {
        return $this->validationRulesForInsert($datas);
    }

    public function filterInvalidFields($datas)
    {
        $validFields = array_keys($this->validationRulesForInsert($datas));
        foreach ($datas as $key => $val) {
            if (!in_array($key, $validFields) || empty($val)) {
                unset($datas[$key]);
            }
        }
        return $datas;
    }

    public function save($datas = null, $where = null)
    {
        $smtpPassword = isset($datas['password_smtp']) ? $datas['password_smtp'] : null;
        $smtpEmail = isset($datas['email_smtp']) ? $datas['email_smtp'] : null;
        $smtpHost = isset($datas['host_smtp']) ? $datas['host_smtp'] : null;
        $colors = 2;
        $this->load->helper('colortext');
        for ($i = 1; $i <= $colors; $i++) {
            $color = 'color' . $i;
            $$color = isset($datas[$color]) ? $datas[$color] : null;
        }
        if ($smtpHost)
            $smtpHost = str_replace('ssl://', '', $smtpHost);
        $configEmail = APPPATH . 'config' . DIRECTORY_SEPARATOR . '/email.php';
        if (is_writable($configEmail) && is_readable($configEmail)) {
            $emailConfigContent = file_get_contents($configEmail);
            if ($smtpEmail) {
                $emailConfigContent = preg_replace(
                    '#\$config\[\'smtp_user\'\] = (\'|")(.*)(\'|");?#', '$config[\'smtp_user\'] = \'' . $smtpEmail . '\';', $emailConfigContent
                );
            }
            if ($smtpHost) {
                $emailConfigContent = preg_replace(
                    '#\$config\[\'smtp_host\'\] = (\'|")(.*)(\'|");?#', '$config[\'smtp_host\'] = \'ssl://' . $smtpHost . '\';', $emailConfigContent
                );
            }
            if ($smtpPassword) {
                $emailConfigContent = preg_replace(
                    '#\$config\[\'smtp_pass\'\] = (\'|")(.*)(\'|");?#', '$config[\'smtp_pass\'] = \'' . $smtpPassword . '\';', $emailConfigContent
                );
            }
            file_put_contents($configEmail, $emailConfigContent);
        } else {
            add_error('La configuration smtp n\'a pas été mise à jour car le fichier de '
                . 'configuration n\'est pas accessible en écriture ou en lecture.');
        }
        $cssPath = BASEPATH . '../assets/vendor/css/material-dashboard.base.css';
        $cssCustomPath = BASEPATH . '../assets/vendor/css/custom.css';
        if (is_readable($cssPath)) {
            $fileExists = file_exists($cssCustomPath);
            $fileWritable = $fileExists && is_writable($cssCustomPath);
            if ($fileWritable || !$fileExists) {
                $css = file_get_contents($cssPath);
                for ($i = 1; $i <= $colors; $i++) {
                    $color = 'color' . $i;
                    if ($$color) {
                        list($r, $g, $b) = hex2rgb($$color);
                        $css = str_replace('{' . $color . '}', '#' . $$color, $css);
                        $css = str_replace('{' . $color . 'comp}', colortext($$color), $css);
                        $css = str_replace('{' . $color . 'shadow}', "rgba($r,$g,$b, 0.4)", $css);
                        $css = str_replace('{' . $color . 'shadow2}', "rgba($r,$g,$b, 0.2)", $css);
                        $css = str_replace('{' . $color . 'shadow3}', "rgba($r,$g,$b, 0.14)", $css);
                        $css = str_replace('{' . $color . 'shadow4}', "rgba($r,$g,$b, 0.12)", $css);
                    }
                }

                file_put_contents(BASEPATH . '../assets/vendor/css/custom.css', $css);
            } else {
                add_error('La configuration des couleurs n\'a pas pu être faite car le fichier '
                    . 'de style n\'est pas accessible en écriture');
            }
        } else {
            add_error('La configuration des couleurs n\'a pas pu être faite car le fichier '
                . 'de style de référence n\'est pas accessible en lecture');
        }
        unset($datas['password_smtp']);
        $this->setValues($datas);
    }

    public function insert($datas = null)
    {
        $this->save($datas);
    }

    public function update($datas = null, $where = null)
    {
        $this->save($datas, $where);
    }

    public function uploadPaths()
    {
        $configurationUpload = 'uploads/configuration';
        return array(
            'app_login_image' => $configurationUpload,
            'app_sidebar_image' => $configurationUpload
        );
    }

}

?>
