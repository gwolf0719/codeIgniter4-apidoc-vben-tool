<?php
//  php spark make:vben tablename     
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class MakeVbenCommand extends BaseCommand
{
    protected $group = 'Generators';
    protected $name = 'make:vben';
    protected $description = 'Generates vben-admin files for a given name.';

    protected $db; // 數據庫實例
    public function __construct($group, $name)
    {
        parent::__construct($group, $name);
        // 獲取數據庫實例
        $this->db = \Config\Database::connect();
    }
    public function run(array $params)
    {
        // 獲取命令參數（例如 'sa_manager'）
        $name = array_shift($params);
        if (empty($name)) {
            CLI::error("You must provide a name.");
            return;
        }

        // 將名稱轉換為駝峰命名法 (CamelCase)
        $formattedName = $this->convertToCamelCase($name);  // 例如 'SaManager'

        // API 模板文件處理
        $apiTemplateContent = file_get_contents(APPPATH . 'Commands/Templates/vben-admin/api_template.ts');
        $apiTemplateContent = str_replace('PlaceHoderName', $formattedName, $apiTemplateContent);

        $apiPath = APPPATH . "../vben-admin/$name/src/api/";
        if (!is_dir($apiPath)) {
            mkdir($apiPath, 0777, true);
        }
        file_put_contents($apiPath . "{$formattedName}.ts", $apiTemplateContent);

        // 路由模板文件處理
        $routesTemplateContent = file_get_contents(APPPATH . 'Commands/Templates/vben-admin/routes_template.ts');
        $routesTemplateContent = str_replace(['PlaceHoderName', 'placehoder_name'], [$formattedName, $name], $routesTemplateContent);

        $routesPath = APPPATH . "../vben-admin/$name/src/router/routes/modules/";
        if (!is_dir($routesPath)) {
            mkdir($routesPath, 0777, true);
        }
        file_put_contents($routesPath . "{$formattedName}.ts", $routesTemplateContent);

        CLI::write("vben-admin files for '$name' have been generated.", 'green');


        $db = \Config\Database::connect();
        // 先獲取所有欄位的詳細資訊，包括備註
        $query = $db->query("SELECT COLUMN_NAME, COLUMN_COMMENT, COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE  TABLE_NAME = '$name'");
        
        $columns = [];
        foreach ($query->getResult() as $row) {
            // 過濾不需要的欄位
            if (in_array($row->COLUMN_NAME, [ 'deleted_at'])) {
                continue;
            }
            $columns[] = [
                'name' => $row->COLUMN_NAME,
                'comment' => $row->COLUMN_COMMENT,
                'type' => $row->COLUMN_TYPE,
            ];
        }
        



        // 處理 Vue 檔案
        $this->processVueIndexFile($name, 'index_with_placeholders.vue', 'index.vue', $columns);
        $this->processVueFile($name, 'Modal_with_placeholders.vue', 'Modal.vue', $columns);

        CLI::write("vben-admin files for '" . $name . "' have been generated.", 'green');
    }

    private function processVueIndexFile($name, $templateFile, $outputFile, $columns)
    {
        $templatePath = APPPATH . "Commands/Templates/vben-admin/$templateFile";
        $content = file_get_contents($templatePath);

        $columnsString = "";
        $columnsStringetFormConfigSchemas = '';
        ;
        foreach ($columns as $column) {
            
                $columnsString .= "
                    {
                        title: '" . $column['comment'] . "',
                        sorter: true,
                        dataIndex: '" . $column['name']  . "',
                    },";
                $columnsStringetFormConfigSchemas .= "
            {
                field: '" . $column['name'] . "',
                label: '" . $column['comment'] . "',
                component: 'Input',
                colProps: {
                    span: 24,
                },
            },
            ";
            

        }

        
        $content = str_replace('PlaceHoderNamegetBasicColumns', $columnsString, $content);
        $content = str_replace('PlaceHoderNamegetFormConfigSchemas', $columnsStringetFormConfigSchemas, $content);
        $formattedName = $this->convertToCamelCase($name);
        $content = str_replace('PlaceHoderName', $formattedName, $content);
        // placehoder_columns_id
        $content = str_replace('placehoder_columns_id', $name . '_id', $content);
        $dir = APPPATH . "../vben-admin/" . $name . "/src/views/" . $name . "/list/";
        $outputPath = APPPATH . "../vben-admin/" . $name . "/src/views/" . $name . "/list/$outputFile";
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($outputPath, $content);
    }
    private function processVueFile($name, $templateFile, $outputFile, $columns)
    {
        $templatePath = APPPATH . "Commands/Templates/vben-admin/$templateFile";
        $content = file_get_contents($templatePath);
        $columnsString = '';
        foreach ($columns as $column) {
            
                $columnsString .= "
                {
                    field: '" . $column['name'] . "',
                    component: 'Input',
                    label: '" . $column['comment'] . "',
                    componentProps: {
                        placeholder: '請輸入" . $column['comment'] . "',
                        onChange: (e) => {
                            console.log(e);
                        },
                    },
                },";
            
            
        }
        // 替換佔位符
        $content = str_replace('PlaceHoderNameFormSchema', $columnsString, $content);
        $formattedName = $this->convertToCamelCase($name);
        $content = str_replace('PlaceHoderName', $formattedName, $content);
        // placehoder_columns_id
        $content = str_replace('placehoder_columns_id', $name . '_id', $content);
        $dir = APPPATH . "../vben-admin/" . $name . "/src/views/" . $name . "/list/";
        $outputPath = APPPATH . "../vben-admin/" . $name . "/src/views/" . $name . "/list/$outputFile";
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($outputPath, $content);
    }

    private function convertToCamelCase($string)
    {
        $result = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
        return $result;
    }
}
