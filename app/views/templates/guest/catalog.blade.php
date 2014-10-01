@extends(Helper::layout())
@section('style')
@stop
@section('content')
@if(Auth::check())
    @if(in_array(Auth::user()->group()->pluck('name'),array('admin','moderator')))
        <?php $valid_order = FALSE;?>
    @else
        <?php $valid_order = TRUE;?>
    @endif
@else
    <?php $valid_order = FALSE;?>
@endif
<main class="registration">
    {{ $page->block('top_h2') }}
    <div class="print-link">
        <a href="#">Распечатать каталог</a> <span class="icon icon-print"></span>
    </div>
    <div class="desc">
    {{ $page->block('top_desc') }}
    </div>
    <div class="banner banner--red">
        <span>В августе месяце скидка 30%<br>на курсы по охране труда.</span>
    </div>
    <form class="{{ @$valid_order ? 'authenticated ' : '' }}accordion-form clearfix">
        <div class="accordion">
            <div class="accordion-header acc-build">
                <div class="accordion-img" style="background-image: url(images/directions/01.png);"></div>
                <h3>Строительство</h3>
                <div class="acc-courses">
                    8 курсов
                </div>
            </div>
            <div class="accordion-body">
                <table>
                    <tr>
                        <th>
                            <div class="checkbox-container">
                                <input type="checkbox" class="main-checkbox">
                            </div>
                            Название
                        </th>
                        <th>Код</th>
                        <th>Часы</th>
                        <th>Цена</th>
                    </tr>
                    <tr>
                        <td>
                            <div class="checkbox-container">
                                <input type="checkbox" value="01" class="secondary-checkbox">
                            </div>
                            Схемы планировочной организации земельного участка, в том числе на особо опасных, технически сложных и уникальных объектах.
                        </td>
                        <td>
                            <span class="code">П-234</span>
                        </td>
                        <td>
                            <span class="code">72–140</span>
                        </td>
                        <td>
                            <span class="price">3 000.–</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="checkbox-container">
                                <input type="checkbox" value="02" class="secondary-checkbox">
                            </div>
                            Внутренние системы и сети электроснабжения, слаботочные системы, диспетчеризация, автоматизация, управление инженерными системами, в том числе на особо опасных, технически сложных и уникальных объектах.
                        </td>
                        <td>
                            <span class="code">П-234</span>
                        </td>
                        <td>
                            <span class="code">72–140</span>
                        </td>
                        <td>
                            <span class="price">3 000.–</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="checkbox-container">
                                <input type="checkbox" value="03" class="secondary-checkbox">
                            </div>
                            Обоснование радиационной и ядерной защиты, в том числе на особо опасных, технически сложных и уникальных объектах
                        </td>
                        <td>
                            <span class="code">П-234</span>
                        </td>
                        <td>
                            <span class="code">72–140</span>
                        </td>
                        <td>
                            <span class="price">3 000.–</span>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="accordion-header acc-proj">
                <div class="accordion-img" style="background-image: url(images/directions/02.png);"></div>
                <h3>Проектирование</h3>
                <div class="acc-courses">
                    43 курса
                </div>
            </div>
            <div class="accordion-body">
                <table>
                    <tr>
                        <th>
                            <div class="checkbox-container">
                                <input type="checkbox" class="main-checkbox">
                            </div>
                            Название
                        </th>
                        <th>Код</th>
                        <th>Часы</th>
                        <th>Цена</th>
                    </tr>
                    <tr>
                        <td>
                            <div class="checkbox-container">
                                <input type="checkbox" value="04" class="secondary-checkbox">
                            </div>
                            Схемы планировочной организации земельного участка, в том числе на особо опасных, технически сложных и уникальных объектах.
                        </td>
                        <td>
                            <span class="code">П-234</span>
                        </td>
                        <td>
                            <span class="code">72–140</span>
                        </td>
                        <td>
                            <span class="price">3 000.–</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="checkbox-container">
                                <input type="checkbox" value="05" class="secondary-checkbox">
                            </div>
                            Внутренние системы и сети электроснабжения, слаботочные системы, диспетчеризация, автоматизация, управление инженерными системами, в том числе на особо опасных, технически сложных и уникальных объектах.
                        </td>
                        <td>
                            <span class="code">П-234</span>
                        </td>
                        <td>
                            <span class="code">72–140</span>
                        </td>
                        <td>
                            <span class="price">3 000.–</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="checkbox-container">
                                <input type="checkbox" value="06" class="secondary-checkbox">
                            </div>
                            Обоснование радиационной и ядерной защиты, в том числе на особо опасных, технически сложных и уникальных объектах
                        </td>
                        <td>
                            <span class="code">П-234</span>
                        </td>
                        <td>
                            <span class="code">72–140</span>
                        </td>
                        <td>
                            <span class="price">3 000.–</span>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="accordion-header acc-eng">
                <div class="accordion-img" style="background-image: url(images/directions/03.png);"></div>
                <h3>Инженерные изыскания</h3>
                <div class="acc-courses">
                    21 курс
                </div>
            </div>
            <div class="accordion-body">
                <table>
                    <tr>
                        <th>
                            <div class="checkbox-container">
                                <input type="checkbox" class="main-checkbox">
                            </div>
                            Название
                        </th>
                        <th>Код</th>
                        <th>Часы</th>
                        <th>Цена</th>
                    </tr>
                    <tr>
                        <td>
                            <div class="checkbox-container">
                                <input type="checkbox" value="07" class="secondary-checkbox">
                            </div>
                            Схемы планировочной организации земельного участка, в том числе на особо опасных, технически сложных и уникальных объектах.
                        </td>
                        <td>
                            <span class="code">П-234</span>
                        </td>
                        <td>
                            <span class="code">72–140</span>
                        </td>
                        <td>
                            <span class="price">3 000.–</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="checkbox-container">
                                <input type="checkbox" value="08" class="secondary-checkbox">
                            </div>
                            Внутренние системы и сети электроснабжения, слаботочные системы, диспетчеризация, автоматизация, управление инженерными системами, в том числе на особо опасных, технически сложных и уникальных объектах.
                        </td>
                        <td>
                            <span class="code">П-234</span>
                        </td>
                        <td>
                            <span class="code">72–140</span>
                        </td>
                        <td>
                            <span class="price">3 000.–</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="checkbox-container">
                                <input type="checkbox" value="09" class="secondary-checkbox">
                            </div>
                            Обоснование радиационной и ядерной защиты, в том числе на особо опасных, технически сложных и уникальных объектах
                        </td>
                        <td>
                            <span class="code">П-234</span>
                        </td>
                        <td>
                            <span class="code">72–140</span>
                        </td>
                        <td>
                            <span class="price">3 000.–</span>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="accordion-header acc-fire">
                <div class="accordion-img" style="background-image: url(images/directions/04.png);"></div>
                <h3>Пожарная безопасность</h3>
                <div class="acc-courses">
                    8 курсов
                </div>
            </div>
            <div class="accordion-body">
                <table>
                    <tr>
                        <th>
                            <div class="checkbox-container">
                                <input type="checkbox" class="main-checkbox">
                            </div>
                            Название
                        </th>
                        <th>Код</th>
                        <th>Часы</th>
                        <th>Цена</th>
                    </tr>
                    <tr>
                        <td>
                            <div class="checkbox-container">
                                <input type="checkbox" value="10" class="secondary-checkbox">
                            </div>
                            Схемы планировочной организации земельного участка, в том числе на особо опасных, технически сложных и уникальных объектах.
                        </td>
                        <td>
                            <span class="code">П-234</span>
                        </td>
                        <td>
                            <span class="code">72–140</span>
                        </td>
                        <td>
                            <span class="price">3 000.–</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="checkbox-container">
                                <input type="checkbox" value="11" class="secondary-checkbox">
                            </div>
                            Внутренние системы и сети электроснабжения, слаботочные системы, диспетчеризация, автоматизация, управление инженерными системами, в том числе на особо опасных, технически сложных и уникальных объектах.
                        </td>
                        <td>
                            <span class="code">П-234</span>
                        </td>
                        <td>
                            <span class="code">72–140</span>
                        </td>
                        <td>
                            <span class="price">3 000.–</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="checkbox-container">
                                <input type="checkbox" value="12" class="secondary-checkbox">
                            </div>
                            Обоснование радиационной и ядерной защиты, в том числе на особо опасных, технически сложных и уникальных объектах
                        </td>
                        <td>
                            <span class="code">П-234</span>
                        </td>
                        <td>
                            <span class="code">72–140</span>
                        </td>
                        <td>
                            <span class="price">3 000.–</span>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="accordion-header acc-prov">
                <div class="accordion-img" style="background-image: url(images/directions/05.png);"></div>
                <h3>Инженерное обеспечение</h3>
                <div class="acc-courses">
                    43 курса
                </div>
            </div>
            <div class="accordion-body">
                <table>
                    <tr>
                        <th>
                            <div class="checkbox-container">
                                <input type="checkbox" class="main-checkbox">
                            </div>
                            Название
                        </th>
                        <th>Код</th>
                        <th>Часы</th>
                        <th>Цена</th>
                    </tr>
                    <tr>
                        <td>
                            <div class="checkbox-container">
                                <input type="checkbox" value="13" class="secondary-checkbox">
                            </div>
                            Схемы планировочной организации земельного участка, в том числе на особо опасных, технически сложных и уникальных объектах.
                        </td>
                        <td>
                            <span class="code">П-234</span>
                        </td>
                        <td>
                            <span class="code">72–140</span>
                        </td>
                        <td>
                            <span class="price">3 000.–</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="checkbox-container">
                                <input type="checkbox" value="14" class="secondary-checkbox">
                            </div>
                            Внутренние системы и сети электроснабжения, слаботочные системы, диспетчеризация, автоматизация, управление инженерными системами, в том числе на особо опасных, технически сложных и уникальных объектах.
                        </td>
                        <td>
                            <span class="code">П-234</span>
                        </td>
                        <td>
                            <span class="code">72–140</span>
                        </td>
                        <td>
                            <span class="price">3 000.–</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="checkbox-container">
                                <input type="checkbox" value="15" class="secondary-checkbox">
                            </div>
                            Обоснование радиационной и ядерной защиты, в том числе на особо опасных, технически сложных и уникальных объектах
                        </td>
                        <td>
                            <span class="code">П-234</span>
                        </td>
                        <td>
                            <span class="code">72–140</span>
                        </td>
                        <td>
                            <span class="price">3 000.–</span>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="accordion-header acc-constr">
                <div class="accordion-img" style="background-image: url(images/directions/06.png);"></div>
                <h3>Конструкции гражданских зданий</h3>
                <div class="acc-courses">
                    21 курс
                </div>
            </div>
            <div class="accordion-body">
                <table>
                    <tr>
                        <th>
                            <div class="checkbox-container">
                                <input type="checkbox" class="main-checkbox">
                            </div>
                            Название
                        </th>
                        <th>Код</th>
                        <th>Часы</th>
                        <th>Цена</th>
                    </tr>
                    <tr>
                        <td>
                            <div class="checkbox-container">
                                <input type="checkbox" value="16" class="secondary-checkbox">
                            </div>
                            Схемы планировочной организации земельного участка, в том числе на особо опасных, технически сложных и уникальных объектах.
                        </td>
                        <td>
                            <span class="code">П-234</span>
                        </td>
                        <td>
                            <span class="code">72–140</span>
                        </td>
                        <td>
                            <span class="price">3 000.–</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="checkbox-container">
                                <input type="checkbox" value="17" class="secondary-checkbox">
                            </div>
                            Внутренние системы и сети электроснабжения, слаботочные системы, диспетчеризация, автоматизация, управление инженерными системами, в том числе на особо опасных, технически сложных и уникальных объектах.
                        </td>
                        <td>
                            <span class="code">П-234</span>
                        </td>
                        <td>
                            <span class="code">72–140</span>
                        </td>
                        <td>
                            <span class="price">3 000.–</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="checkbox-container">
                                <input type="checkbox" value="18" class="secondary-checkbox">
                            </div>
                            Обоснование радиационной и ядерной защиты, в том числе на особо опасных, технически сложных и уникальных объектах
                        </td>
                        <td>
                            <span class="code">П-234</span>
                        </td>
                        <td>
                            <span class="code">72–140</span>
                        </td>
                        <td>
                            <span class="price">3 000.–</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <button class="btn btn--bordered btn--blue pull-right">
            Далее
        </button>
    </form>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop