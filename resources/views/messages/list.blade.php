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
                <th style="width: 300px">
                  Nội dung
                </th>
                <th style="text-align: right;">
                  2 số
                </th>
                <th style="text-align: right;">
                  3-4 số
                </th>
                <th width="1%">
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
                <td width="300" style="width: 30px;">
                  <a  data-toggle="tooltip" href="{{ route('messages.detail', $mess->id) }}" title="{{ $mess->content }}">
                  @if(strlen($mess->content) > 50)
                  {!! substr($mess->content, 0, 50) !!}...
                  @else
                  {!! $mess->content !!}
                  @endif
                  </a>
                </td>
                <td style="text-align: right;font-weight: bold;">
                  {{ number_format($mess->calTotal2So($mess->id)) }}
                </td>
                <td style="text-align: right;font-weight: bold;">
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
<style type="text/css">
  .table td{
    font-size: 20px;
  }
  .tooltip-inner {
    max-width: 350px;
    /* If max-width does not work, try using width instead */
    width: 350px; 
    text-align: left
}
</style>
@stop
@section('js')
<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip(); 
});
</script>
@stop