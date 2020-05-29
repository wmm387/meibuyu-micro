# 导出Excel csv工具使用说明
1、导出Excel，不使用模板的情况
```$xslt
    $exporter = new Exporter(Exporter::EXPORTER_TYPE_XLSX,'导出产品信息');#不用模板的情况
    
    //填充表头
    $title = ['产品'，'颜色'，'尺码'，'sku']
    $list = Product::with(["color","size"])->get()->toArray();
    $exporter->append($title)
    ->append($list,['name','color.name','size.name|未设定尺码','sku'])//填充数据
    ->setUnprotectRange("A1:B".$exporter->getCurrentRowIndex())//设置保护
    ->download(Exporter::DOWNLOAD_TYPE_STREAM,"产品数据");//下载数据
```

2、导出Excel，使用模板的情况
```$xslt
    //根据模板导出器
    $exporter = new Exporter(Exporter::EXPORTER_TYPE_XLSX,'导出产品信息'，'template/xxx.xlsx');#使用模板来生成
    
    
    
    //新增sheet并使用它
    $exporter->addSheet('颜色参考表', true);
    //填充表头
    $title = ['颜色id'，'中文颜色名'，'英文颜色名']
    $exporter->append($title)
    ->append(Color::get(["id", "cn_name", "en_name"]))
    ->setUnprotectRange();//全表保护
    //设置保护
    //使用别的sheet表
    $exporter->setSheetByIndex(0)->setBeginRowIndex(2)->setBeginColumnChar("C");//从第三列开始填充数据
    //填充数据
    $list = Product::with(["color","size"])->get()->toArray();
    $exporter->append($list,['name','color.name','size.name|未设定尺码','sku']);
    
    $file= $exporter->download(Exporter::DOWNLOAD_TYPE_RETURN_FILE_PATH,"产品数据");
    
    echo $file;
        
```
