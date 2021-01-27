<?
//out($_REQUEST);

$method = 'crm.deal.list';
$first_day = date('Y-m-01').'T00:00:00';
$last_day  = date('Y-m-t').'T23:59:59';
$cat_events_id = 2;
$cat_services_id = 4;

$queryUrl_events = 'https://'.$_REQUEST["DOMAIN"].'/rest/'.$method.'.json';
$queryUrl_services = 'https://'.$_REQUEST["DOMAIN"].'/rest/'.$method.'.json';
$params_events = [
    'filter' => [
        '>DATE_CREATE' => $first_day,
        '<DATE_CREATE' => $last_day,
        'CATEGORY_ID' => $cat_events_id
    ],
    'select' => ["TITLE", "UF_CRM_1611740375", "UF_CRM_1611740401",  "UF_CRM_1611740429", "UF_CRM_5F469A6252513", "UF_CRM_1611130017433", "UF_CRM_1611130046604", "UF_CRM_1611131701778", "UF_CRM_1611131771158", "UF_CRM_1611131949069", "UF_CRM_1611139386226", "UF_CRM_1611139439073"]
];
$params_services = [
    'filter' => [
        '>DATE_CREATE' => $first_day,
        '<DATE_CREATE' => $last_day,
        'CATEGORY_ID' => $cat_services_id,
    ],

    'select' => ["TITLE", "UF_CRM_1611740375", "UF_CRM_1611740401",  "UF_CRM_1611740429", "UF_CRM_5F469A6252513", "UF_CRM_1611130017433", "UF_CRM_1611130046604", "UF_CRM_1611131701778", "UF_CRM_1611131771158", "UF_CRM_1611131949069", "UF_CRM_1611139386226", "UF_CRM_1611139439073"]
];
$queryData_events = http_build_query(array_merge($params_events, array("auth" => $_REQUEST["AUTH_ID"])));
$queryData_services = http_build_query(array_merge($params_services, array("auth" => $_REQUEST["AUTH_ID"])));

$curl_events = curl_init();
curl_setopt_array($curl_events, array(
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_POST => 1,
    CURLOPT_HEADER => 0,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $queryUrl_events,
    CURLOPT_POSTFIELDS => $queryData_events
));

$curl_services = curl_init();
curl_setopt_array($curl_services, array(
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_POST => 1,
    CURLOPT_HEADER => 0,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $queryUrl_services,
    CURLOPT_POSTFIELDS => $queryData_services
));

$result_events = json_decode(curl_exec($curl_events), true);
curl_close($curl_events);

$result_services = json_decode(curl_exec($curl_services), true);
curl_close($curl_services);

//out($result_events);

function out($var, $var_name = ''){
    echo '<pre style="outline: 1px dashed red; padding:5px; margin:10px; color:white; background:black">';
    if(!empty($var_name)){
        echo '<h3>'.$var_name.'</h3>';
    }
    if(is_string($var)){
        $var = htmlspecialchars($var);
    }
    print_r($var);
    echo '</pre>';
}

?>
<!doctype html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="libs/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/main.css">
        <script src="libs/jquery/jquery.min.js"></script>
        <script src="libs/table2excel/table2excel.min.js"></script>
        <script src="js/main.js"></script>
        <title>Мои отчёты</title>
    </head>
    <body>
        <div class="wrapper">
            <h1>Отчёты о завяках на услуги и мероприятия</h1>
            <p>Данное приложение показывает <strong>50</strong> последних заявок c <strong><?=date('Y-01-m')?></strong> по <strong><?=date('Y-t-m')?></strong></p>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#events">Мероприятия</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#services">Услуги</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="events">
                    <div class="row download">
                        <div class="col-6 download-title h6">
                            <h3>Мероприятия</h3>
                        </div>
                        <div class="col-6 download-wrap">
                            <button class="btn btn-success" id="download-events" <? if(empty($result_events['result'])){echo 'disabled'; }?>>Выгрузить в Excel</button>
                        </div>
                    </div>
                    <? if(empty($result_events['result'])){ ?>
                        <p style="color:red"><? echo "За данный период заявок на мероприятия не поступало!"; ?></p>
                    <? } ?>
                    <table class="table table-striped">
                        <tr>
                            <th>ID</th>
                            <th>Мероприятие</th>
                            <th>Данные клиента</th>
                            <th>Данные организации/физ. лица</th>
                        </tr>
                        <? foreach ($result_events['result'] as $event){ ?>
                            <tr>
                                <td><?=$event["ID"]?></td>
                                <td style="max-width:350px"><?=$event["TITLE"]?></td>
                                <td style="min-width:250px">
                                    <div>
                                        <strong>Имя клиента: </strong>
                                        <div>
                                            <? if($event["UF_CRM_1611740375"] != ''){
                                                echo $event["UF_CRM_1611740375"];
                                            }else{
                                                echo '-';
                                            } ?>
                                        </div>
                                    </div>
                                    <div>
                                        <strong>Телефон: </strong>
                                        <div>
                                            <? if($event["UF_CRM_1611740401"] != ''){
                                                echo $event["UF_CRM_1611740401"];
                                            }else{
                                                echo '-';
                                            } ?>
                                        </div>
                                    </div>
                                    <div>
                                        <strong>E-mail: </strong>
                                        <div>
                                            <? if($event["UF_CRM_1611740429"] != ''){
                                                echo $event["UF_CRM_1611740429"];
                                            }else{
                                                echo '-';
                                            } ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <? if($event["UF_CRM_5F469A6252513"] != ''){?>
                                        <div>
                                            <strong>ИНН: </strong>
                                            <div>
                                                <?=$event["UF_CRM_5F469A6252513"]; ?>
                                            </div>
                                        </div>
                                    <?}?>
                                    <? if($event["UF_CRM_1611130017433"] != ''){?>
                                        <div>
                                            <strong>Регистрационная форма: </strong>
                                            <div>
                                                <?=$event["UF_CRM_1611130017433"];?>
                                            </div>
                                        </div>
                                    <? } ?>
                                    <? if($event["UF_CRM_1611130046604"] != ''){ ?>
                                        <div>
                                            <strong>Название организации: </strong>
                                            <div>
                                                <?=$event["UF_CRM_1611130046604"];?>
                                            </div>
                                        </div>
                                    <? } ?>
                                    <? if($event["UF_CRM_1611131701778"] != ''){ ?>
                                        <div>
                                            <strong>Должность: </strong>
                                            <div>
                                                <?=$event["UF_CRM_1611131701778"]; ?>
                                            </div>
                                        </div>
                                    <? } ?>
                                    <? if($event["UF_CRM_1611131771158"] != ''){ ?>
                                        <div>
                                            <strong>Муниципалитет: </strong>
                                            <div>
                                                <?=$service["UF_CRM_1611131771158"]; ?>
                                            </div>
                                        </div>
                                    <? } ?>
                                    <? if($event["UF_CRM_1611131949069"] != ''){ ?>
                                        <div>
                                            <strong>Основной ОКВЭД: </strong>
                                            <div>
                                                <?=$service["UF_CRM_1611131949069"];?>
                                            </div>
                                        </div>
                                    <? } ?>
                                    <? if($event["UF_CRM_1611139386226"] != ''){?>
                                        <div>
                                            <strong>Группа граждан: </strong>
                                            <div>
                                                <?=$service["UF_CRM_1611139386226"];?>
                                            </div>
                                        </div>
                                    <? } ?>
                                    <? if($event["UF_CRM_1611139439073"] != ''){ ?>
                                        <div>
                                            <strong>Cобираетесь ли вы стать предпринимателем? </strong>
                                            <div>
                                                <?=$service["UF_CRM_1611139439073"];?>
                                            </div>
                                        </div>
                                    <? } ?>
                                </td>
                            </tr>
                        <? } ?>
                    </table>
                    <!-- Скрытая таблица для выгрузки мероприятий -->
                    <table class="table table-striped hidden" id="table-events">
                        <tr>
                            <th>ID</th>
                            <th>Услуга</th>
                            <th>Имя клиента</th>
                            <th>Телефон клиента</th>
                            <th>E-mail клиента</th>
                            <th>ИНН</th>
                            <th>Регистрационная форма</th>
                            <th>Название организации</th>
                            <th>Должность</th>
                            <th>Муниципалитет</th>
                            <th>Основной ОКВЭД</th>
                            <th>Группа граждан</th>
                            <th>Собираетесь ли вы стать предпринимателем?</th>
                        </tr>
                        <? foreach ($result_events['result'] as $events){ ?>
                            <tr>
                                <td><?=$event["ID"]?></td>
                                <td style="max-width:350px"><?=$event["TITLE"]?></td>
                                <td style="min-width:250px">
                                    <? if($event["UF_CRM_1611740375"] != ''){
                                        echo $event["UF_CRM_1611740375"];
                                    }else{
                                        echo '-';
                                    } ?>
                                </td>
                                <td>
                                    <? if($event["UF_CRM_1611740401"] != ''){
                                        echo $event["UF_CRM_1611740401"];
                                    }else{
                                        echo '-';
                                    } ?>
                                </td>
                                <td>
                                    <? if($event["UF_CRM_1611740429"] != ''){
                                        echo $event["UF_CRM_1611740429"];
                                    }else{
                                        echo '-';
                                    } ?>
                                </td>
                                <td>
                                    <? if($event["UF_CRM_5F469A6252513"] != ''){?>
                                        <?=$event["UF_CRM_5F469A6252513"]; ?>
                                    <?}else{echo 'не заполнено';} ?>
                                </td>
                                <td>
                                    <? if($event["UF_CRM_1611130017433"] != ''){?>
                                        <?=$event["UF_CRM_1611130017433"];?>
                                    <?}else{echo 'не заполнено';} ?>
                                </td>
                                <td>
                                    <? if($event["UF_CRM_1611130046604"] != ''){ ?>
                                        <?=$event["UF_CRM_1611130046604"];?>
                                    <?}else{echo 'не заполнено';} ?>
                                </td>
                                <td>
                                    <? if($event["UF_CRM_1611131701778"] != ''){ ?>
                                        <?=$event["UF_CRM_1611131701778"]; ?>
                                    <?}else{echo 'не заполнено';} ?>
                                </td>
                                <td>
                                    <? if($event["UF_CRM_1611131771158"] != ''){ ?>
                                        <?=$event["UF_CRM_1611131771158"]; ?>
                                    <?}else{echo 'не заполнено';} ?>
                                </td>
                                <td>
                                    <?if($event["UF_CRM_1611131949069"] != ''){ ?>
                                        <?=$event["UF_CRM_1611131949069"];?>
                                    <?}else{echo 'не заполнено';} ?>
                                </td>
                                <td>
                                    <? if($event["UF_CRM_1611139386226"] != ''){?>
                                        <?=$event["UF_CRM_1611139386226"];?>
                                    <?}else{echo 'не заполнено';} ?>
                                </td>
                                <td>
                                    <? if($event["UF_CRM_1611139439073"] != ''){ ?>
                                        <?=$event["UF_CRM_1611139439073"];?>
                                    <?}else{echo 'не заполнено';} ?>
                                </td>
                            </tr>
                        <? } ?>
                    </table>
                </div>
                <div class="tab-pane" id="services">
                    <div class="row download">
                        <div class="col-6 download-title h6">
                            <h3>Услуги</h3>
                        </div>
                        <div class="col-6 download-wrap">
                            <button class="btn btn-success" <? if(empty($result_services['result'])){echo 'disabled'; }?> id="download-services">Выгрузить в Excel</button>
                        </div>
                    </div>
                    <? if(empty($result_services['result'])){ ?>
                        <p style="color:red"><? echo "За данный период заявок на услуги не поступало!"; ?></p>
                    <? } ?>
                    <table class="table table-striped">
                        <tr>
                            <th>ID</th>
                            <th>Услуга</th>
                            <th>Данные клиента</th>
                            <th>Данные организации/физ. лица</th>
                        </tr>
                        <? foreach ($result_services['result'] as $service){ ?>
                            <tr>
                                <td><?=$service["ID"]?></td>
                                <td style="max-width:350px"><?=$service["TITLE"]?></td>
                                <td style="min-width:250px">
                                    <div>
                                        <strong>Имя клиента: </strong>
                                        <div>
                                            <? if($service["UF_CRM_1611740375"] != ''){
                                            echo $service["UF_CRM_1611740375"];
                                            }else{
                                                echo '-';
                                            } ?>
                                        </div>
                                    </div>
                                    <div>
                                        <strong>Телефон: </strong>
                                        <div>
                                            <? if($service["UF_CRM_1611740401"] != ''){
                                                echo $service["UF_CRM_1611740401"];
                                            }else{
                                                echo '-';
                                            } ?>
                                        </div>
                                    </div>
                                    <div>
                                        <strong>E-mail: </strong>
                                        <div>
                                            <? if($service["UF_CRM_1611740429"] != ''){
                                                echo $service["UF_CRM_1611740429"];
                                            }else{
                                                echo '-';
                                            } ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <? if($service["UF_CRM_5F469A6252513"] != ''){?>
                                        <div>
                                            <strong>ИНН: </strong>
                                            <div>
                                                <?=$service["UF_CRM_5F469A6252513"]; ?>
                                            </div>
                                        </div>
                                    <?}?>
                                    <? if($service["UF_CRM_1611130017433"] != ''){?>
                                        <div>
                                            <strong>Регистрационная форма: </strong>
                                            <div>
                                                <?=$service["UF_CRM_1611130017433"];?>
                                            </div>
                                        </div>
                                    <? } ?>
                                    <? if($service["UF_CRM_1611130046604"] != ''){ ?>
                                        <div>
                                            <strong>Название организации: </strong>
                                            <div>
                                                <?=$service["UF_CRM_1611130046604"];?>
                                            </div>
                                        </div>
                                    <? } ?>
                                    <? if($service["UF_CRM_1611131701778"] != ''){ ?>
                                        <div>
                                            <strong>Должность: </strong>
                                            <div>
                                                 <?=$service["UF_CRM_1611131701778"]; ?>
                                            </div>
                                        </div>
                                    <? } ?>
                                    <? if($service["UF_CRM_1611131771158"] != ''){ ?>
                                        <div>
                                            <strong>Муниципалитет: </strong>
                                            <div>
                                                <?=$service["UF_CRM_1611131771158"]; ?>
                                            </div>
                                        </div>
                                    <? } ?>
                                    <? if($service["UF_CRM_1611131949069"] != ''){ ?>
                                        <div>
                                            <strong>Основной ОКВЭД: </strong>
                                            <div>
                                                <?=$service["UF_CRM_1611131949069"];?>
                                            </div>
                                        </div>
                                    <? } ?>
                                    <? if($service["UF_CRM_1611139386226"] != ''){?>
                                        <div>
                                            <strong>Группа граждан: </strong>
                                            <div>
                                                <?=$service["UF_CRM_1611139386226"];?>
                                            </div>
                                        </div>
                                    <? } ?>
                                    <? if($service["UF_CRM_1611139439073"] != ''){ ?>
                                        <div>
                                            <strong>Cобираетесь ли вы стать предпринимателем? </strong>
                                            <div>
                                                <?=$service["UF_CRM_1611139439073"];?>
                                            </div>
                                        </div>
                                    <? } ?>
                                </td>
                            </tr>
                        <? } ?>
                    </table>
                    <!-- Скрытая таблица для выгрузки услуг -->
                    <table class="table table-striped hidden" id="table-services">
                        <tr>
                            <th>ID</th>
                            <th>Услуга</th>
                            <th>Имя клиента</th>
                            <th>Телефон клиента</th>
                            <th>E-mail клиента</th>
                            <th>ИНН</th>
                            <th>Регистрационная форма</th>
                            <th>Название организации</th>
                            <th>Должность</th>
                            <th>Муниципалитет</th>
                            <th>Основной ОКВЭД</th>
                            <th>Группа граждан</th>
                            <th>Собираетесь ли вы стать предпринимателем?</th>
                        </tr>
                        <? foreach ($result_services['result'] as $service){ ?>
                            <tr>
                                <td><?=$service["ID"]?></td>
                                <td style="max-width:350px"><?=$service["TITLE"]?></td>
                                <td style="min-width:250px">
                                    <? if($service["UF_CRM_1611740375"] != ''){
                                        echo $service["UF_CRM_1611740375"];
                                    }else{
                                        echo '-';
                                    } ?>
                                </td>
                                <td>
                                    <? if($service["UF_CRM_1611740401"] != ''){
                                        echo $service["UF_CRM_1611740401"];
                                    }else{
                                        echo '-';
                                    } ?>
                                </td>
                                <td>
                                    <? if($service["UF_CRM_1611740429"] != ''){
                                        echo $service["UF_CRM_1611740429"];
                                    }else{
                                        echo '-';
                                    } ?>
                                </td>
                                <td>
                                    <? if($service["UF_CRM_5F469A6252513"] != ''){?>
                                    <?=$service["UF_CRM_5F469A6252513"]; ?>
                                    <?}else{echo 'не заполнено';} ?>
                                </td>
                                <td>
                                    <? if($service["UF_CRM_1611130017433"] != ''){?>
                                    <?=$service["UF_CRM_1611130017433"];?>
                                    <?}else{echo 'не заполнено';} ?>
                                </td>
                                <td>
                                    <? if($service["UF_CRM_1611130046604"] != ''){ ?>
                                    <?=$service["UF_CRM_1611130046604"];?>
                                    <?}else{echo 'не заполнено';} ?>
                                </td>
                                <td>
                                    <? if($service["UF_CRM_1611131701778"] != ''){ ?>
                                    <?=$service["UF_CRM_1611131701778"]; ?>
                                    <?}else{echo 'не заполнено';} ?>
                                </td>
                                <td>
                                    <? if($service["UF_CRM_1611131771158"] != ''){ ?>
                                    <?=$service["UF_CRM_1611131771158"]; ?>
                                    <?}else{echo 'не заполнено';} ?>
                                </td>
                                <td>
                                    <?if($service["UF_CRM_1611131949069"] != ''){ ?>
                                    <?=$service["UF_CRM_1611131949069"];?>
                                    <?}else{echo 'не заполнено';} ?>
                                </td>
                                <td>
                                    <? if($service["UF_CRM_1611139386226"] != ''){?>
                                    <?=$service["UF_CRM_1611139386226"];?>
                                    <?}else{echo 'не заполнено';} ?>
                                </td>
                                <td>
                                    <? if($service["UF_CRM_1611139439073"] != ''){ ?>
                                    <?=$service["UF_CRM_1611139439073"];?>
                                    <?}else{echo 'не заполнено';} ?>
                                </td>
                            </tr>
                        <? } ?>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
