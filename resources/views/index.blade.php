{{--引入需要的静态文件 如果在控制器中引用的话，会造成每个页面都引用了该文件 --}}
<link rel="stylesheet" href="/vendor/hxsen/file-selector/file-selector.css">

<div class="form-group {!! !$errors->has($label) ? '' : 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>
    <div class="col-sm-8">
{{--        <meta name="csrf-token" content="{{ csrf_token() }}">--}}
        @include('admin::form.error')

        <div class="controls">
            <a href="#file-browser-{!!$column!!}" class="mailbox-attachment-name" style="word-break:break-all;"
               data-toggle="modal" data-target="#file-browser-{!!$column!!}">
                <button class="btn btn-info" type="button">选择文件</button>
            </a>
            <!-- 模态框（Modal） -->

            <div class="modal fade" id="file-browser-{!!$column!!}" tabindex="-1" role="dialog"
                 aria-labelledby="file-browser-label" aria-hidden="true">
                <div class="modal-dialog" style="width: 90%;height: 100%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="file-browser-label"></h4>
                        </div>
                        <div class="modal-body">
                            <ul class="files clearfix">

                                @if (empty($list))
                                    <li style="height: 200px;border: none;"></li>
                                @else
                                    @foreach($list as $item)
                                        @if(!$item['isDir'])
                                            <li>
                                                <span class="file-select {{$column}}-pic">
                                                    <div class="{{ $type == 'radio' ? 'iradio_minimal-blue' : 'icheckbox_minimal-blue' }} mycheckbox" >
                                                        <input type="{{ $type }}" name="{!!$column!!}-pic" value="{{ $item['name'] }}" style="position: absolute; opacity: 0;"/>
                                                    </div>
                                                </span>

                                                {!! $item['preview'] !!}

                                                <div class="file-info">
                                                    <a href="{{ $item['link'] }}" class="file-name"
                                                       title="{{ $item['name'] }}">
                                                        {{ $item['icon'] }} {{ basename($item['name']) }}
                                                    </a>
                                                    <span class="file-size">
                                                {{ $item['size'] }}&nbsp;
                                                </span>
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                @endif
                            </ul>

                        </div>

                        <div class="modal-footer">
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal -->
            </div>
        </div>
        <input type="hidden" name="{{$name}}" id="{{$column}}" value="{{ json_encode(old($column, $value)) }}">
        <div id="preview-{{$column}}"></div>


        @include('admin::form.help-block')
    </div>
</div>

<script>
    $(function() {
        // 定义新增的图片变量
        let image_{{$column}};
        // 定义网址的默认网址
        let basePath = '{{ $basePath }}';
        // 定义input的框的类型
        let inputType = '{{ $type }}';

        // 指定默认值是数组还是字符串
        if(inputType === 'radio'){
            image_{{$column}} = '';
        }else{
            image_{{$column}} = [];
        }

        // 首次加载图片列表
        if ($('#{{$column}}').val() !== "null") {
            image_{{$column}} = JSON.parse($('#{{$column}}').val());
            $('#preview-{{$column}}').html(preview(image_{{$column}}));
            for (let n = 0; n < $('.{{$column}}-pic>input:checkbox').length; n++) {
                if (image_{{$column}}.indexOf($('.{{$column}}-pic>input:checkbox')[n].value) !== -1) {
                    $('.{{$column}}-pic>input:checkbox')[n].checked = true;
                }
            }
        }
        // 监听个人的checkbox的点击操作
        $('.mycheckbox').click(function(){
            // $(this).toggleClass('checked');
            let imgUrl = basePath + '/' + $(this).find('input[type="'+ inputType +'"]').val();

            if($(this).hasClass('checked')){
                toggleImage(false, imgUrl);
                $(this).removeClass('checked');
            }else{
                toggleImage(true, imgUrl);
                // 单选框移除其他的所有选中
                if(inputType === 'radio') {
                    $('.mycheckbox').removeClass('checked');
                }
                $(this).addClass('checked');
            }
        });

        // 控制显示与隐藏的图片选择框
        $('.modal-dialog').click(function () {
            $('#file-browser-{!!$column!!}').modal('hide');
        });
        $(".modal-content").click(function (event) {
            event.stopPropagation();
        });
        function preview(list) {
            let picList = '';
            // 设定一张图片的样式
            let oneImg = (imgInfo)=> '<span class="file-icon has-img col-sm-2"><img src="' + imgInfo + '" onerror="javascript:this.src=\'/vendor/hxsen/file-selector/file.png\'" alt="Attachment" \/><\/span>';

            // 单选只添加一张图片即可
            if(inputType === 'radio' && list){
                picList = oneImg(list);
            }else{
                for (let i = 0; i < list.length; i++) {
                    picList += oneImg(list[i]);
                }
            }
            return picList;
        }
        // 切换选定的图片的列表
        function toggleImage(status, imgUrl){

            if(inputType === 'radio'){
                if(status){
                    image_{{$column}} = imgUrl;
                }else{
                    image_{{$column}} = '';
                }
                // 加入值
                $('#{{$column}}').val(image_{{$column}});
            }else{
                if(status){
                    image_{{$column}}.push(imgUrl);
                }else{
                    let index = image_{{$column}}.indexOf(imgUrl);
                    if (index > -1) {
                        image_{{$column}}.splice(index, 1);
                    }
                }
                $('#{{$column}}').val(JSON.stringify(image_{{$column}}));
            }

            // 重新渲染页面的值
            $('#preview-{{$column}}').html(preview(image_{{$column}}));
        }
    })
</script>
