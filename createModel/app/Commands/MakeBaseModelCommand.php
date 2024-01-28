<?php namespace App\Commands;
// spark 指令  php spark make:basemodel ModelName tableName
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class MakeBaseModelCommand extends BaseCommand
{
    protected $group       = 'Generators';
    protected $name        = 'make:basemodel';
    protected $description = 'Creates a new model file extending from BaseModel.';

    public function run(array $params)
    {
        // 確保提供了模型名稱
        $name = array_shift($params);
        if (empty($name))
        {
            CLI::error('You must provide a model name.');
            return;
        }
        $tableName = array_shift($params); // 數據表名稱
        if (empty($tableName))
        {
            CLI::error('You must provide a table name.');
            return;
        }

        $ns = CLI::getOption('namespace') ?? APP_NAMESPACE;
        $homePath = APPPATH;

        // 定義文件路徑和模板數據
        $path = $homePath . 'Models/' . $name . '.php';
        $template = <<<EOD
<?php namespace $ns\Models;

use App\Models\BaseModel;

class {name} extends BaseModel
{
    protected \$table            = 'table_name';
    protected \$primaryKey       = 'pk_id';
    protected \$useAutoIncrement = true;
    protected \$returnType       = 'array';
    protected \$useSoftDeletes   = true;
    protected \$protectFields    = true;
    protected \$allowedFields    = [];

    // Dates
    protected \$useTimestamps = true;
    protected \$dateFormat    = 'datetime';
    protected \$createdField  = 'created_at';
    protected \$updatedField  = 'updated_at';
    protected \$deletedField  = 'deleted_at';

    // Validation
    protected \$validationRules      = [];
    protected \$validationMessages   = [];
    protected \$skipValidation       = false;
    protected \$cleanValidationRules = true;

    // Callbacks
    protected \$allowCallbacks = true;
    protected \$beforeInsert   = [];
    protected \$afterInsert    = [];
    protected \$beforeUpdate   = [];
    protected \$afterUpdate    = [];
    protected \$beforeFind     = [];
    protected \$afterFind      = [];
    protected \$beforeDelete   = [];
    protected \$afterDelete    = [];
}
EOD;

        $template = str_replace('{name}', $name, $template);

        $db = \Config\Database::connect();
        $fields = $db->getFieldNames($tableName);
        $template = str_replace('table_name', $tableName, $template);
        $template = str_replace('pk_id', $fields[0], $template);
        // 所有的 fields 都要放入 allowedFields
        $allowedFields = "'" . implode("', '", $fields) . "'";
        $allowedFieldsStr = 'protected $allowedFields    = [' . $allowedFields . '];';
        $template = str_replace('protected $allowedFields    = [];', $allowedFieldsStr, $template);


        // 確保文件不存在
        if (file_exists($path))
        {
            CLI::error("File already exists at: {$path}");
            return;
        }

        // 寫入文件
        helper('filesystem');
        if (!write_file($path, $template))
        {
            CLI::error('Error writing model file.');
            return;
        }

        CLI::write('File created: ' . CLI::color($path, 'green'));
    }
}
