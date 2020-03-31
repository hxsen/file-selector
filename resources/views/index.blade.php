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
                                                    <div class="{{ $type == 'radio' ? 'iradio_minimal-blue' : 'icheckbox_minimal-blue' }} mycheckbox" data-file-path="{{ $item['name'] }}"></div>
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
        <input type="hidden" name="{{$name}}" id="{{$column}}" value="{{ old($column, $value) }}">
        <div id="preview-{{$column}}"></div>


        @include('admin::form.help-block')
    </div>
</div>

<script>
    // 闭包处理元素，限制变量，防止变量污染，主要面向多次的引入该插件的操作
    $(function() {
        /*
         * 定义自己需要的或者已经衍生的变量
         */
        // 定义自己的input字段名
        let column = '{{$column}}';
        // 定义自己得到modal框
        let modal = $("#file-browser-" + column);
        // 定义当前hide的input元素
        let hideInput = $('#' + column);
        // 定义媒体暂存的变量
        let mediaDisk;
        // 定义网址的默认路径开始的部分
        let basePath = '{{ $basePath }}/';
        // 定义input的框的类型
        let inputType = '{{ $type }}';
        // 定义当前可以选择的所有媒体选框的input
        let inputBox = $('input[name="'+ column +'-pic"]:' + inputType);

        // 指定默认值是数组还是字符串
        mediaDisk = hideInput.val();
        if(inputType === 'checkbox'){
            mediaDisk = mediaDisk ? JSON.parse(mediaDisk) : [];
        }

        // 首次加载图片列表
        if (hideInput.val() !== "null") {
            // 布局已经渲染的图片列表
            $('#preview-' + column).html(preview(mediaDisk));
            // 设置当前旧的媒体文件的状态
            let thisVal;
            inputBox.each(function(){
                thisVal = basePath + $(this).val();

                if (mediaDisk.indexOf(thisVal) !== -1) {
                    $(this).parent().addClass('checked');
                }
            });
        }else{
            // 如果是null的情况下，设置value为空字符串
            hideInput.val('');
        }

        // 监听个人的checkbox的点击操作
        modal.find('.mycheckbox').click(function(){
            let imgUrl = basePath + $(this).data('file-path');

            if($(this).hasClass('checked')){
                toggleImage(false, imgUrl);
                $(this).removeClass('checked');
            }else{
                toggleImage(true, imgUrl);
                // 单选框移除其他的所有选中
                if(inputType === 'radio') {
                    // 这是单选操作
                    $(this).parents('.files').find('.mycheckbox').removeClass('checked');
                }
                $(this).addClass('checked');
            }
        });

        // 控制显示与隐藏的图片选择框
        modal.find('.modal-dialog').click(function () {
            $('#file-browser-' + column).modal('hide');
        });
        modal.find(".modal-content").click(function (event) {
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
                    mediaDisk = imgUrl;
                }else{
                    mediaDisk = '';
                }
                // 加入值
                hideInput.val(mediaDisk);
            }else{
                if(status){
                    mediaDisk.push(imgUrl);
                }else{
                    let index = mediaDisk.indexOf(imgUrl);
                    if (index > -1) {
                        mediaDisk.splice(index, 1);
                    }
                }
                hideInput.val(JSON.stringify(mediaDisk));
            }

            // 重新渲染页面的值
            $('#preview-' + column).html(preview(mediaDisk));
        }
    })
</script>
