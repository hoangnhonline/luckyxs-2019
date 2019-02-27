@extends('layout')

@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Danh sách tin nhắn</h4>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th width="1%">
                  #
                </th>
                <th>
                  Nội dung
                </th>
                <th>
                  2 số
                </th>
                <th>
                  3-4 số
                </th>
                <th>
                  Thời gian nhận
                </th>
              </tr>
            </thead>
            <tbody>
              @php
              $i = 0;
              @endphp
              @if($messageList->count() > 0)
              @foreach($messageList as $mess)
              @php 
              $i++;
              @endphp
              <tr>
                <td class="font-weight-medium">
                  {{ $i }}
                </td>
                <td>
                  <a href="{{ route('messages.detail', $mess->id) }}">
                  {!! $mess->content !!}
                  </a>
                </td>
                <td>
                  {{ number_format($mess->calTotal2So($mess->id)) }}
                </td>
                <td>
                  {{ number_format($mess->calTotal3So($mess->id)) }}
                </td>
                <td>
                  {{ date('d-m-Y H:i', strtotime($mess->created_at)) }}
                </td>
              </tr>
              @endforeach
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div> 
@stop