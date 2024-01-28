<?php
// php spark make:apicontroller ControllerName ModelName TableName

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CreateAPIController extends BaseCommand
{
    protected $group = 'API';
    protected $name = 'make:apicontroller';
    protected $description = 'Creates a new API controller with standard methods.';

    public function run(array $params)
    {
        // 確保提供了足夠的參數
        if (count($params) < 3) {
            // 如果參數只有一個則當作是控制器名稱
            if (count($params) == 1) {
                $params[1] = 'Mod'.$params[0];
                $params[2] =strtolower(preg_replace('/(?<=[a-z])([A-Z])/', '_$1', $params[0]));
            } else {
                CLI::write('Error: Not enough arguments. Usage: make:apicontroller [ControllerName] [ModelName] [TableName]', 'red');
                return;
            }
        }

        // 解析參數
        [$controllerName, $modelName, $tableName] = $params;
        
        


        // 定義模板路徑和新文件的目標路徑
        $templatePath = APPPATH . 'Commands/Templates/ControllerTemplate.php';
        $targetPath = APPPATH . 'Controllers/' . $controllerName . '.php';

        // 讀取模板
        if (!file_exists($templatePath)) {
            CLI::write('Error: Controller template does not exist.', 'red');
            return;
        }
        $template = file_get_contents($templatePath);

        $db = \Config\Database::connect();
        // 檢查資料表是否存在
        if (!$db->tableExists($tableName)) {
            CLI::write('Error: Table '.$tableName.' does not exist.', 'red');
            return;
        }
        // 先獲取所有欄位的詳細資訊，包括備註
        $query = $db->query("SELECT COLUMN_NAME, COLUMN_COMMENT, COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE  TABLE_NAME = '$tableName'");
        $api_doc_placeholder = '';
        
        foreach ($query->getResult() as $row) {
            // 過濾不需要的欄位
            if (in_array($row->COLUMN_NAME, ['created_at', 'updated_at', 'deleted_at'])) {
                continue;
            }
            // $row->COLUMN_TYPE
            // 如果 $row->COLUMN_TYPE 包含 varchar 則為字串 String
            if (strpos($row->COLUMN_TYPE, 'varchar') !== false) {
                $type = 'String';
            } else if (strpos($row->COLUMN_TYPE, 'int') !== false) {
                $type = 'Number';
            } else if (strpos($row->COLUMN_TYPE, 'text') !== false) {
                $type = 'String';
            } else if (strpos($row->COLUMN_TYPE, 'datetime') !== false) {
                $type = 'String';
            } else if (strpos($row->COLUMN_TYPE, 'date') !== false) {
                $type = 'String';
            } else {
                $type = 'String';
            }
            


            // 構建 API 文檔占位符
            $api_doc_placeholder .= ' * @apiBody {' . $type  . '} ' . $row->COLUMN_NAME . ' ' . $row->COLUMN_COMMENT . "\n";
        }
        // 建構 apiParamExample {json} Request-Example
        $api_doc_placeholder .= ' * @apiParamExample {json} Request-Example:' . "\n";
        $api_doc_placeholder .= ' *     {' . "\n";
        foreach ($query->getResult() as $row) {
            
            if (in_array($row->COLUMN_NAME, ['created_at', 'updated_at', 'deleted_at'])) {
                continue;
            }
            $api_doc_placeholder .= ' *       "' . $row->COLUMN_NAME . '": "",' . "\n";
        }
        $api_doc_placeholder .= ' *     }' . "\n";


        
        $template = str_replace('api_doc_placeholder', $api_doc_placeholder, $template);

        // 替換模板中的佔位符
        $template = str_replace(['ControllerNamePlaceholder', 'ModelPlaceholder', 'TableNamePlaceholder','model_placeholder'], [$controllerName, $modelName, $tableName,$tableName], $template);


        // 寫入新的控制器文件
        if (!write_file($targetPath, $template)) {
            CLI::write('Error: Failed to write controller file.', 'red');
            return;
        }

        CLI::write('Controller created successfully.', 'green');
        

        CLI::write('Please run "sh apidoc.sh" to generate API documentation.', 'yellow');

    }
}
