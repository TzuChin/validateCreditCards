## Feature
1. 提供針對**單一檔案**或**特定資料夾**下所有檔案的內容進行信用卡號規則驗證。
2. 主要以批次校驗為目的。  
3. 以產出 Report 呈現結果。

## 操作說明
1. 檔案內容格式應遵循下方範例內容: (每一行文字皆為一組卡號)
    ```
    1231231231231231
    0202020202020202
    7897897897897897
    ```
2. 以 command line 方式進行操作  
   Example:  
   `php validateCard.php`
3. 提供以下參數選項:  
   
    Options|Description|Default
    ------:|:----------|:-----
    -s     | 提供檔案路徑或資料夾路徑 | ./
    -d     | 提供產出的檔案路徑及檔名 | ./test.txt
    
    Example:  
    `php validateCard.php -s=~/analysis/ -d=./result.txt`
4. 最後呈現結果如下  
   
   Title|Description
   -|-
   TOTAL|總筆數
   MATCHED|符合信用卡號數量
   NOT MATCHED|未符合卡號規則的數量
   DUPLICATED|重複資料數(包含符合&未符合)

    Example:  
   `TOTAL: 4056 MATCHED: 205 NOT MATCHED: 79 DUPLICATED: 3772`

---
## 備註
若使用上遇到任何問題(操作疑問、Notice、Warning、功能建議)，  
歡迎發 Issue 或 Pull Request。