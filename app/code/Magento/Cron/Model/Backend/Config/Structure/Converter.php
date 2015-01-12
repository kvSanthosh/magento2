<?php
/**
 * @copyright Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 */
namespace Magento\Cron\Model\Backend\Config\Structure;

class Converter
{
    /**
     * @var \Magento\Cron\Model\Groups\Config\Data
     */
    protected $groupsConfig;

    /**
     * @param \Magento\Cron\Model\Groups\Config\Data $groupsConfig
     */
    public function __construct(\Magento\Cron\Model\Groups\Config\Data $groupsConfig)
    {
        $this->groupsConfig = $groupsConfig;
    }

    /**
     * Modify system configuration for cron
     *
     * @param \Magento\Backend\Model\Config\Structure\Converter $subject
     * @param array $result
     *
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function afterConvert(\Magento\Backend\Model\Config\Structure\Converter $subject, array $result)
    {
        $groupIterator = 0;
        if (!isset($result['config']['system']['sections']['system']['children']['cron']['children']['template'])) {
            return $result;
        }
        foreach ($this->groupsConfig->get() as $group => $fields) {
            $template = $result['config']['system']['sections']['system']['children']['cron']['children']['template'];
            $template['id'] = $group;
            $template['label'] .= $group;
            $template['sortOrder'] += $groupIterator++;

            $fieldIterator = 0;
            foreach ($fields as $fieldName => $value) {
                $template['children'][$fieldName]['path'] = 'system/cron/' . $group;
                $template['children'][$fieldName]['sortOrder'] += $fieldIterator++;
            }
            $result['config']['system']['sections']['system']['children']['cron']['children'][$group] = $template;
        }
        unset($result['config']['system']['sections']['system']['children']['cron']['children']['template']);
        return $result;
    }
}
