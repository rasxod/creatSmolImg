creatSmolImg
====
Создает маленьке картинки в дирректории по параметрам



## How
> кладем **make_thumb.php** в папку с картинками
>
> После выполнения у вас появятся 2 категории с резервными копиями изображений и с результатами(папка имеет имя текущего месяца и года)
> из результируйщей папки скопируйте в корень с заменой

**start CMD**

> в открывшемся окне

**cd E:\my_dir\parts**

> если не переходит в нужный диск добвьте **/d**

**cd /d E:\my_dir\parts**

> если PHP установлен и раскоментировано **extension=php_gd2.dll**

**php make_thumd.php**

>если не установлен качаем 

**PHP**
https://cloud.mail.ru/public/d946c803b47c/php-5.2.17-nts-Win32-VC6-x86.msi

**php.ini**
https://cloud.mail.ru/public/3be97f266e6b/php.ini


#### обратите внимание на переменные
> стр 33-34

> префикс для результатов и разделительмежду префиксом и исходным названием картинки

```php
$smallPrefix = '';
$betweenPrefix = ''; 
```

> стр 48-49

> размеры изображений, 
>если указать пустое значение в любом из них, то для результатов будут использованы изображения исходных размеров.

```php
$w = ''; // ширина
$h = '1024'; // высота
```

#### для того чтобы конвертнуть PNG в JPG

     php make_thumd.php pngToGpg


**Более подробно на http://z1q.ru**

