<div class="card-body">
    <div class="row font-weight-bold">
        <div class="col-md-2">Sender</div>
        <div class="col-md-10 d-flex justify-content-between">
            <span>Message | Attachments</span>
            <span>Date/Time</span>
        </div>
    </div>
    
    @foreach ($conversations as $conversation)
        <div class="row p-3 my-2 border-top">
            <div class="col-md-2">{{ $conversation->author_name ?? 'Unknown Author' }}</div>
            <div class="col-md-10">
                <div class="Conversation">
                    <div class="float-end">
                        <span class="message-time">{{ \Carbon\Carbon::parse($conversation->created_at)->format('d-m-Y h:i A') }}</span>
                    </div>
                    <div class="Conversation-message">
                    {{ $conversation->name }}
                    <div class="clearfix">&nbsp;</div>
                    {!! $conversation->description !!}
                        
                        @if($conversation->attachments && is_array(json_decode($conversation->attachments)))
                            @php
                                $attachments = json_decode($conversation->attachments, true);
                            @endphp
                            <div class="attachments">
                                @foreach($attachments as $attachment)
                                    <div class="attachment-item">
                                        @php
                                            $filePath = asset('storage/app/public/' . $attachment['path']);
                                            $fileExtension = strtolower(pathinfo($attachment['original_name'], PATHINFO_EXTENSION));
                                        @endphp
                                        <a href="{{ $filePath }}" target="_blank">{{ $attachment['original_name'] }}</a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>