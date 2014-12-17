<!doctype html>
<html class="no-js">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{{ isset($page_title) ? $page_title : Config::get('app.default_page_title') }}}</title>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400,600&amp;subset=latin,cyrillic" rel="stylesheet" type="text/css">
</head>
<body>
    <main style="width: 936px; font-family: 'Open Sans'">
        @if(isset($SpisokSluschateley))
        <?php
            $spisok = array();
            $accountDiscount = User_organization::where('id',$SpisokSluschateley->user_id)->pluck('discount');
            $coursesCountDiscount = coursesCountDiscount($SpisokSluschateley->listeners);
            foreach($SpisokSluschateley->listeners as $listener):
                $spisok[$listener->course->id]['count']++;
                $spisok[$listener->course->id]['code'] = $listener->course->code;
                $spisok[$listener->course->id]['title'] = $listener->course->title;
                $spisok[$listener->course->id]['price'] = $listener->course->price;
                $discount = calculateDiscount(array($listener->course->direction->discount,$listener->course->discount,$accountDiscount,$coursesCountDiscount));
                $spisok[$listener->course->id]['discount'] = 0;
                if($discount > 0):
                    $spisok[$listener->course->id]['discount'] = round($spisok[$listener->course->id]['price']*round($discount/100,2));
                endif;
                $spisok[$listener->course->id]['summa'] = calculateDiscount(array($listener->course->direction->discount,$listener->course->discount,$accountDiscount,$coursesCountDiscount),$spisok[$listener->course->id]['count']*$spisok[$listener->course->id]['price']);
                if(empty($spisok[$listener->course->id]['discount'])):
                    $spisok[$listener->course->id]['discount'] = 0;
                endif;
            endforeach;
            foreach($spisok as $course):
                $VsegoNaimenovaliy += $course['count'];
                $KolichestvoNaimenovaliy++;
            endforeach;
        ?>
        <?php ob_start();?>
        <table cellspacing="0" cellpadding="0" border="1" style="margin-bottom: 40px; font-family: 'Open Sans'">
            <tbody>
                <tr>
                    <td style="padding: 3pt 6pt 3pt 6pt;"><p align="center"><strong>№ п/п</strong></p></td>
                    <td style="padding: 3pt 6pt 3pt 6pt;"><p align="center"><strong>Наименование курса повышения квалификации</strong></p></td>
                    <td style="padding: 3pt 6pt 3pt 6pt;"><p align="center"><strong>Кол-во</strong></p></td>
                    <td style="padding: 3pt 6pt 3pt 6pt;"><p align="center"><strong>Ед.</strong></p></td>
                    <td style="padding: 3pt 6pt 3pt 6pt; white-space: nowrap;"><p align="center"><strong>Цена (руб)</strong></p></td>
                    <td style="padding: 3pt 6pt 3pt 6pt; white-space: nowrap;"><p align="center"><strong>Скидка</strong></p></td>
                    <td style="padding: 3pt 6pt 3pt 6pt; white-space: nowrap;"><p align="center"><strong>Сумма (руб)</strong></p></td>
                </tr>
                <?php $index = 1;?>
            @foreach($spisok as $course_id => $course)
                <tr>
                    <td style="padding: 3pt 6pt 3pt 6pt;"><p align="center">{{ $index }}</p></td>
                    <td style="padding: 3pt 6pt 3pt 6pt;"><p align="center">{{ $course['code'] }}. {{ $course['title'] }}</p></td>
                    <td style="padding: 3pt 6pt 3pt 6pt;"><p align="center">{{ $course['count'] }}</p></td>
                    <td style="padding: 3pt 6pt 3pt 6pt;"><p align="center">курс</p></td>
                    <td style="padding: 3pt 6pt 3pt 6pt; white-space: nowrap;"><p align="center">{{ number_format($course['price'],2,',',' ') }}</p></td>
                    <td style="padding: 3pt 6pt 3pt 6pt; white-space: nowrap;"><p align="center">{{ number_format($course['discount'],2,',',' ') }}</p></td>
                    <td style="padding: 3pt 6pt 3pt 6pt; white-space: nowrap;"><p align="center">{{ number_format($course['summa'],2,',',' ') }}</p></td>
                </tr>
                <?php $index++; ?>
            @endforeach
            </tbody>
        </table>
        <?php $SpisokSluschateleyDlyaAkta = ob_get_clean(); ?>
        @endif
        @if(isset($template) && File::exists($template))
            <?php require_once($template);?>
        @endif
    </main>
</body>
</html>
