<!doctype html>
<html class="no-js">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{{ isset($page_title) ? $page_title : Config::get('app.default_page_title') }}}</title>
</head>
<body>
    <main>
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
                $spisok[$listener->course->id]['discount'] = calculateDiscount(array($listener->course->direction->discount,$listener->course->discount,$accountDiscount,$coursesCountDiscount));
                $spisok[$listener->course->id]['summa'] = calculateDiscount(array($listener->course->direction->discount,$listener->course->discount,$accountDiscount,$coursesCountDiscount),$spisok[$listener->course->id]['count']*$spisok[$listener->course->id]['price']);
            endforeach;
            foreach($spisok as $course):
                $VsegoNaimenovaliy += $course['count'];
                $KolichestvoNaimenovaliy++;
                if(!$spisok[$listener->course->id]['discount']):
                    $spisok[$listener->course->id]['discount'] = 0;
                endif;
            endforeach;
        ?>
        <?php ob_start();?>
        <table>
            <tbody>
                <tr>
                    <td><p align="center"><strong>№ п/п</strong></p></td>
                    <td><p align="center"><strong>Наименование курса повышения квалификации</strong></p></td>
                    <td><p align="center"><strong>Кол-во</strong></p></td>
                    <td><p align="center"><strong>Ед.</strong></p></td>
                    <td><p align="center"><strong>Цена (руб)</strong></p></td>
                    <td><p align="center"><strong>Скидка (%)</strong></p></td>
                    <td><p align="center"><strong>Сумма (руб)</strong></p></td>
                </tr>
                <?php $index = 1;?>
            @foreach($spisok as $course_id => $course)
                <tr>
                    <td><p align="center">{{ $index }}</p></td>
                    <td><p align="center">{{ $course['code'] }}. {{ $course['title'] }}</p></td>
                    <td><p align="center">{{ $course['count'] }}</p></td>
                    <td><p align="center">курс</p></td>
                    <td><p align="center">{{ number_format($course['price'],2,',',' ') }}</p></td>
                    <td><p align="center">{{ $course['discount'] }}</p></td>
                    <td><p align="center">{{ number_format($course['summa'],2,',',' ') }}</p></td>
                </tr>
                <?php $index++; ?>
            @endforeach
            </tbody>
        </table>
        <?php $SpisokSluschateleyDlyaScheta = ob_get_clean(); ?>
        @endif
        @if(isset($template) && File::exists($template))
            <?php require_once($template);?>
        @endif
    </main>
</body>
</html>
