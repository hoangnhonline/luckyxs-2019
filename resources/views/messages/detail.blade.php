@extends('layout')

@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin">
    <div class="card">
      <div class="card-body">
      	<a href="{{ route('messages.list') }}" class="btn btn-inverse-light btn-sm">Quay lại</a>
        <h3 class="text-primary" style="margin-top: 10px;">{!! $detail->content !!}</h3>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th width="1%">
                  #
                </th>
                <th>
                  Đài
                </th>
                <th>
                  Loại đề
                </th>
                <th>Số</th>
                <th style="text-align: right;">
                  Số tiền
                </th>
                <th style="text-align: right;">
                  Tổng trước cò
                </th>
                <th style="text-align: right;">
                  Tổng sau cò
                </th>                
              </tr>
            </thead>
            <tbody>
              @php
              $i = 0;
              @endphp
              @if($betList->count() > 0)
              @foreach($betList as $bet)
              @php 
              $i++;
              @endphp
              <tr>
                <td class="font-weight-medium">
                  {{ $i }}
                </td>
                <td>
                	{{ $bet->channel->name }}
                </td>
                <td>
                 	{{ $bet->betType->content }}
                </td>
                <td>
                	{{ $bet->number_1 }}
                	@if($bet->number_2)
                	-{{ $bet->number_2 }}
                	@endif
                </td>
                <td align="right">
                	{{ $bet->price }}
                </td>
                <td align="right">
                	{{ $bet->total }}
                </td>
                <td align="right">
                	
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