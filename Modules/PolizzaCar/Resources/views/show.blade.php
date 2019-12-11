@extends('layouts.app')

@section('content')
    <div class="row">
        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        <div class="header-buttons">
                            @if(!$disableNextPrev)
                                <div class="btn-group next-prev-btn-group" role="group">

                                    @if($prev_record)
                                        <a href="{{ route($routes['show'],$prev_record) }}"
                                           title="@lang('core::core.crud.prev')"
                                           class="btn btn-primary waves-effect btn-crud btn-prev">@lang('core::core.crud.prev')</a>
                                    @endif

                                    @if($next_record)
                                        <a href="{{ route($routes['show'],$next_record) }}"
                                           title="@lang('core::core.crud.next')"
                                           class="btn btn-primary waves-effect btn-crud btn-next">@lang('core::core.crud.next')</a>
                                    @endif
                                </div>
                            @endif

                                <div class="btn-group btn-crud pull-right">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        @lang('core::core.more') <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        @foreach($actionButtons as $link)
                                            <li>
                                                {{ Html::link($link['href'],$link['label'],$link['attr']) }}
                                            </li>
                                        @endforeach

                                            @if($permissions['destroy'] == '' or Auth::user()->hasPermissionTo($permissions['destroy']))
                                                <li>
                                                    {!! Form::open(['route' => [$routes['destroy'], $entity], 'method' => 'delete']) !!}

                                                    {!! Form::button(trans('core::core.crud.delete'), [ 'type' => 'submit', 'class' => '"btn btn-block btn-link  waves-effect waves-block', 'onclick' => "return confirm($.i18n._('are_you_sure'))" ]) !!}

                                                    {!! Form::close() !!}

                                                </li>
                                            @endif

                                    </ul>
                                </div>

                            <a href="{{ route($routes['index']) }}"
                               class="btn btn-primary waves-effect btn-back btn-crud">@lang('core::core.crud.back')</a>

                            @if($permissions['update'] == '' or Auth::user()->hasPermissionTo($permissions['update']))
                                <a href="{{ route($routes['edit'],$entity) }}"
                                   class="btn btn-primary waves-effect btn-edit btn-crud">@lang('core::core.crud.edit')</a>
                            @endif

                        </div>

                        <div class="header-text">
                            @lang($language_file.'.module') - @lang('core::core.crud.details')
                            <small>@lang($language_file.'.module_description')</small>
                        </div>

                    </h2>

                </div>
                <div class="body">
                    <div class="row">

                        @if($show_fileds_count > 1 || $hasExtensions)

                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <ul class="nav nav-tabs tab-nav-right tabs-left" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="#tab_details" data-toggle="tab"
                                           title="@lang('core::core.tabs.details')">
                                            @issetvaance($baseIcons,'details_icon')
                                                <i class="material-icons">folder</i>
                                            @endissetvaance
                                            @issetvaance($baseIcons,'details_label')
                                                @lang('core::core.tabs.details')
                                            @endissetvaance
                                        </a>
                                    </li>

                                    @foreach($relationTabs as $tabKey => $tab)

                                        @if(Auth::user()->hasPermissionTo($tab['permissions']['browse']))
                                            <li role="presentation">

                                                <a class="relation-tab" data-table-related="{{ $tab['htmlTable']->getTableAttribute('id') }}" data-table-new="{{ $tab['newRecordsTable']->getTableAttribute('id')  }}" data-id="tab_{{$tabKey}}" href="#tab_{{$tabKey}}" data-toggle="tab" title="@lang($language_file.'.tabs.'.$tabKey)">
                                                    <i class="material-icons">{{$tab['icon']}}</i>
                                                    @lang($language_file.'.tabs.'.$tabKey)
                                                </a>
                                            </li>
                                        @endif

                                    @endforeach

                                    @if($commentableExtension)
                                        <li role="presentation">
                                            <a href="#tab_comments" data-toggle="tab"
                                               title="@lang('core::core.tabs.comments')">
                                                @issetvaance($baseIcons,'comments_icon')
                                                    <i class="material-icons">chat</i>
                                                @endissetvaance
                                                @issetvaance($baseIcons,'comments_label')
                                                    @lang('core::core.tabs.comments')
                                                @endissetvaance
                                            </a>
                                        </li>
                                    @endif
                                    @if($attachmentsExtension)
                                        <li role="presentation">
                                            <a href="#tab_attachments" data-toggle="tab"
                                               title="@lang('core::core.tabs.attachments')">
                                                @issetvaance($baseIcons,'attachments_icon')
                                                    <i class="material-icons">attach_file</i>
                                                @endissetvaance
                                                @issetvaance($baseIcons,'attachments_label')
                                                    @lang('core::core.tabs.attachments')
                                                @endissetvaance
                                            </a>
                                        </li>
                                    @endif
                                    @if($actityLogDatatable != null )
                                        <li role="presentation">
                                            <a href="#tab_updates" data-toggle="tab"
                                               title="@lang('core::core.tabs.updates')">
                                                @issetvaance($baseIcons,'activity_log_icon')
                                                    <i class="material-icons">change_history</i>
                                                @endissetvaance
                                                @issetvaance($baseIcons,'activity_log_label')
                                                    @lang('core::core.tabs.updates')
                                                @endissetvaance
                                            </a>
                                        </li>
                                    @endif
                                </ul>

                            </div>

                        @endif


                        <div class="col-lg-10 col-md-10 col-sm-10">

                            <div class="tab-content">


                                <div role="tabpanel" class="tab-pane active" id="tab_details">

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        @foreach($customShowButtons as $btn)
                                            {!! Html::customButton($btn) !!}
                                        @endforeach


                                    </div>

                                    @foreach($show_fields as $panelName => $panel)

                                        <div class="collapsible">

                                        {{ Html::section($language_file,$panelName) }}
                                            <div class="panel-content">
                                        @foreach($panel as $fieldName => $options)

                                                    @if($fieldName == 'rows')

                                                        @include('orders::partial.rows')

                                                    @else

                                                        @if(!isset($options['in_show_view']) || $options['in_show_view'])
                                                            {{
                                                                Html::renderField($entity,$fieldName,$options,$language_file)
                                                            }}
                                                        @endif

                                                    @endif

                                        @endforeach
                                            </div>

                                        </div>

                                    @endforeach

                                    <!-- append pdf parts -->
                                    @if($hasPdfs)
                                        @foreach($pdfshowFields as $panelName => $panel)
                                            <div class="collapsible">

                                                {{ Html::section($language_file,$panelName) }}

                                                <div class="panel-content">
                                                    @foreach($panel as $fieldName => $options)

                                                        {{
                                                            Html::renderField($entity,$fieldName,$options,$language_file)
                                                        }}
                                                    @endforeach
                                                </div>

                                            </div>

                                        @endforeach
                                    @else

                                    @endif

                                    @include('core::crud.partial.entity_created_at')

                                </div>

                                @include('core::crud.module.quick_edit')


                                @foreach($relationTabs as $tabKey => $tab)
                                    @if(Auth::user()->hasPermissionTo($tab['permissions']['browse']))
                                        <div role="tabpanel" class="tab-pane" id="tab_{{$tabKey}}">

                                            <div class="related_module_wrapper">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                                        @if($tab['select']['allow'])

                                                            @if(Auth::user()->hasPermissionTo($tab['permissions']['update']))
                                                                <div class="select_relation_wrapper">
                                                                    <a href="#" class="select btn btn-primary waves-effect modal-relation">@lang('core::core.btn.select')</a>

                                                                    <div id="modal_{{$tabKey}}" class="modal fade" role="dialog">
                                                                        <div class="modal-dialog modal-xl">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                    @if(isset($tab['select']['modal_title']))
                                                                                        <h4 class="modal-title">@lang($tab['select']['modal_title'])</h4>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="modal-body linked-records">
                                                                                    <div class="col-lg-12 col-md-12 col-sm-12 linked-records">
                                                                                        @include('core::crud.relation.relation',['datatable' => $tab['newRecordsTable'],'entity'=>$entity,'tab'=>$tab])
                                                                                    </div>

                                                                                </div>
                                                                                <div class="modal-footer">

                                                                                    @include('core::crud.relation.link',['tabkey'=>$tabKey,'entityId' => $entityIdentifier,'route'=>$tab['route']['bind_selected']])

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            @endif
                                                        @endif

                                                        @if($tab['create']['allow'])
                                                            @if(Auth::user()->hasPermissionTo($tab['permissions']['create']))
                                                                <div class="create_new_relation_wrapper">
                                                                    <a href="#" class="select btn btn-primary waves-effect modal-new-relation">@lang('core::core.btn.new')</a>

                                                                    <div data-create-route="{{ route($tab['route']['create'],$tab['create']['post_create_bind']) }}" id="modal_create_{{$tabKey}}"
                                                                         class="modal fade" role="dialog">
                                                                        <div class="modal-dialog modal-lg">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                    @if(isset($tab['create']['modal_title']))
                                                                                        <h4 class="modal-title">@lang($tab['create']['modal_title'])</h4>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="modal-body">

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 linked-records" id="linked_{{$tabKey}}">
                                                        @include('core::crud.relation.relation',['datatable' => $tab['htmlTable'],'entity'=>$entity,'tab'=>$tab])
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach


                                @if($commentableExtension)
                                    <div role="tabpanel" class="tab-pane" id="tab_comments">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            @include('core::extension.comments.list',['entity'=>$entity])
                                        </div>
                                    </div>
                                @endif

                                @if($attachmentsExtension)
                                    <div role="tabpanel" class="tab-pane" id="tab_attachments">

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            @include('core::extension.attachments.list',['entity'=>$entity,'permissions'=>$permissions])
                                        </div>

                                    </div>
                                @endif


                                @if($actityLogDatatable !=  null )
                                    <div role="tabpanel" class="tab-pane" id="tab_updates">

                                        <div class="table-responsive col-lg-12 col-md-12">
                                            @include('core::extension.activity_log.table')
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    @foreach($includeViews as $v)
        @include($v)
    @endforeach

    <div class="modal fade " id="modalUploadFile" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 10080!important;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div class="header">
                            <h3>
                                <div class="header-text">
                                    Carica @lang('PolizzaCar::PolizzaCar.signed_doc_pdf')
                                </div>
                            </h3>
                        </div>
                </div>
                <div class="modal-body">
                    <!-- form_start -->
                    
                    <div class="flashmessage"></div>
                        <input type='hidden' id='polizzacarId' name="polizzacarId" value="{{ $entity->id }}">
                        <div class="form-group">
                            <div class="file-loading">
                                <input id="pdf_signed_contract" type="file" name="pdf_signed_contract" class="file" data-overwrite-initial="false" data-min-file-count="1"  data-msg-placeholder="@lang('PolizzaCar::PolizzaCar.signed_doc_pdf')...">
                            </div>
                            
                        </div>
                    
                    <!-- form_end -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success btnSave">@lang('core::core.SAVE_CHANGES')</button>
                    <button type="button" class="btn btn-error" data-dismiss="modal">@lang('core::core.CLOSE')</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade " id="modalUploadFile2" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 10080!important;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div class="header">
                            <h3>
                                <div class="header-text">
                                        Carica @lang('PolizzaCar::PolizzaCar.payment_proof_pdf')
                                </div>
                            </h3>
                        </div>
                </div>
                <div class="modal-body ">
                    <!-- form_start -->
                    <div class="flashmessage2"></div>
                        <input type='hidden' id='polizzacarId' name="polizzacarId" value="{{ $entity->id }}">
                        <div class="form-group">
                            
                            <div class="file-loading">
                                <input id="pdf_payment_proof" type="file" name="pdf_payment_proof" class="file" data-overwrite-initial="false" data-min-file-count="1"  data-msg-placeholder="@lang('PolizzaCar::PolizzaCar.payment_proof_pdf')...">
                            </div>
                        </div>
                    
                    <!-- form_end -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success btnSave2">@lang('core::core.SAVE_CHANGES')</button>
                    <button type="button" class="btn btn-error" data-dismiss="modal">@lang('core::core.CLOSE')</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade " id="signAjaxForm" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 10080!important;">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body ">
                    <div class="iframe_holder">
                            <!-- Page Loader -->
                                <div id="iframe_loader" style="color: #84a5dd; margin: 0 auto" class="la-ball-scale-multiple la-3x">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                                <iframe id="docuSigniFrame" width="100%" style="min-height: 700px;border: 0;"></iframe>
                        </div>
                    <!-- #END# Page Loader -->
                </div>
            </div>
        </div>
    </div>

    

@endsection

@push('css')
@foreach($cssFiles as $file)
    <link rel="stylesheet" href="{!! Module::asset($moduleName.':css/'.$file) !!}"></link>
@endforeach
@endpush

@push('scripts')
@foreach($jsFiles as $jsFile)
    <script src="{!! Module::asset($moduleName.':js/'.$jsFile) !!}"></script>
@endforeach

@endpush