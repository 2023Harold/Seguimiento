@foreach (session('flash_notification', collect())->toArray() as $message)
    @if ($message['overlay'])
        @include('flash::modal', [
            'modalClass' => 'flash-modal',
            'title'      => $message['title'],
            'body'       => $message['message']
        ])
    @else
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-{{ $message['level'] }} {{ $message['important'] ? 'alert-important' : '' }}" role="alert">
                    @if ($message['important'])
                        <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-hidden="true"></button>
                    @endif
                    {!! $message['message'] !!}
                    <script>
                        /*setTimeout(function(){
                            $(".btn-close").parent().remove();
                        }, 5000);*/
                    </script>
                </div>
            </div>
        </div>
    @endif
@endforeach
{{ session()->forget('flash_notification') }}
