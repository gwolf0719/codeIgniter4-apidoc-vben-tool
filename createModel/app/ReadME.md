
## 依照資料表建構 Model 檔案
> 我預設所有和資料表相關的 Model 檔案都是 Mod開頭的駝峰命名檔案（資料表名稱需要為小寫底線的蛇式命名）
```
php spark make:basemodel ModelName tableName
```
> 這樣會建構出對應的 Model 檔案，主要會將所有的資料表欄位填入這樣後面呼叫會比較輕鬆

## install
> 請將 app 的整個目錄下的檔案放到 codeIgniter4 的 app 目錄中
> 注意要將 Model 檔案放到 app/Models 資料夾中，因為裡面會預設繼承 BaseModel 而不是 CodeIgniter\Model
