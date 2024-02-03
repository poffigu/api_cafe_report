## API Cafe Report

### 1. Запуск
```shell
docker-compose up -d --build
```

### 2. Линки

phpMyAdmin: http://localhost:8001/

Таблица отчета: http://localhost:8000/getReceipts

### 3. Подзапросы для графиков
* Выручка: http://localhost:8000/getPlus
* Наличные: http://localhost:8000/getPlusCash
* Безналичный расчет: http://localhost:8000/getPlusCashless
* Кредитные карты: http://localhost:8000/getPlusCard
* Средний чек: http://localhost:8000/getAverageReceipt
* Средний гость: http://localhost:8000/getAverageClient
* Удаления из чека после оплаты: http://localhost:8000/getMinusAfter
* Удаления из чека до оплаты: http://localhost:8000/getMinusBefore
* Количество чеков: http://localhost:8000/getCountReceipts
* Количество гостей: http://localhost:8000/getCountClients
