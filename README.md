# Тестовое задание

## Описание

 Сущесвует некая виртуальная компания, которая занимается предоставлением услуг аренды телефонных номеров. \
 У этой компании есть ограничнные мощности серверов. \
 В один момент времени, желательно, чтобы не было более 100 одновременных звонков\
 Ведется журнал звонков, который содержит для каждого звонка метку веремени начала (формата Y-m-d H:i:s) и длительность в секундах\
 Журнал звонков сохранен в файл data.json и приложен к этому заданию\
 Руководитель компании хочет держать руку на пульсе и поэтому изъявлет желание видеть информацию из журнала в двух разрезах
 1. В какие метки времени (посекундно) в течении одного исследуемого дня были перегрузки серверов
 
 |  **Метка времени в секундах, H:i:s** | **Максимальная нагрузка в период времени** |
 |--------------------------------------|--------------------------------------------|
 | 12:42:28                             | 201                                        |
 | 15:48:28                             | 101                                        |
 
 2. Поминутная динамика максимального количества одновременных звонков
 
 |  **Метка времени в минутах, Y-m-d H:i** | **Максимальная нагрузка в период времени** | **Наличие перегрузки сервера** |
 |-----------------------------------------|--------------------------------------------|--------------------------------|
 | 2020-01-27 00:10	                       | 5                                          | ok                             |
 | 2020-01-27 00:12	                       | 5                                          | ok                             |
 | 2020-01-27 01:12	                       | 105                                        | overload                       |
 
Часть работ по формированию представлений уже выполнена. Описана в абстрактном классе CallsReport, который имеет ряд зависимостей

## Для реализации необходимо
0. Подключай ClassLoader и вперед. В качестве точки входа можешь использовать index.php
1. Реализовать класс наследник от CallsReport
2. В методе fillCallDtoSpl класса CallsReport наполнить объект \TestTask\callDtoSpl данными из источинка data.json
3. Вывести результат посекундной перегрузки сервера в таблицу метод buildServerOverLoadTable
4. Вывести результат поминутной нагрузки на сервера в таблицу метод buildServerLoadTable
