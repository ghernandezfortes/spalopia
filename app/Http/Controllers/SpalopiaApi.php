<?php

namespace Spalopia\Http\Controllers;

use Illuminate\Http\Request;
use Spalopia\Service;
use Spalopia\Schedule;
use Spalopia\Reservation;
use Carbon\Carbon;
use DB;

class SpalopiaApi extends Controller
{
    // retornamos todos los servicios
    public function getServices()
    {
        return json_encode(Service::all());
    }

    // retornamos todos los horarios
    public function getAllSchedules()
    {
        return json_encode(Schedule::all());
    }

    // retornamos los horarios y los servicios por dia
    public function getSchedulesByDay(Request $request)
    {
        // array para controlar los errores
        $response = array(
            'result' => array(),
            'error' => false,
            'message' => ''
        );

        // comprobamos que las fechas son correctas
        $date_start = new Carbon($request->input('date_start'));
        $date_end = new Carbon($request->input('date_end'));


        if ($date_start->greaterThan($date_end))
        {
            $response['error'] = true;
            $response['message'] = 'Start date greater than the end date';
            return json_encode($response);
        }


        $date_start = $request->input('date_start');
        $date_end = $request->input('date_end');

        // realizamos la consulta de los servicios disponibles con sus reservas
        $result = DB::select(DB::raw("select *, services.id as serv_id from schedules
            join services on services.id = schedules.service_id
            left join reservations on reservations.date_reservation = schedules.date
            where date BETWEEN '" . $date_start . "' and '" . $date_end . "' and reservations.service_id = schedules.service_id
            order by schedules.date asc"));
        
        // realizamos una consulta de los servicios sin reservas
        $result2 = DB::select(DB::raw("select *, schedules.service_id as serv_id from schedules where date BETWEEN '" . $date_start . "' and '" . $date_end . "'"));
        
        // unimos ambas consultas
        $result = array_merge($result, $result2);

        // si no hay resultamos, se lo mostramos al usuario
        if (count($result) == 0) 
        {
            $response['error'] = $this->formatServices($result);
            $response['message'] = 'There are not services for that day';
        }
        else
        {
            // formateamos los datos para un mejor tratamiendo en el cliente
            $response['result'] = $this->formatServices($result);
        }

        return json_encode($response);
    }

    // recepcion y guardado de nueva reserva
    public function sendReservation(Request $request) 
    {
        // array para controlar los errores
        $response = array(
            'message' => 'Reservation saved satisfactorily',
            'error' => false
        );

        $date = new Carbon($request->input('date'));

        // consultamos si hay servicios para el dia y horas indicados
        $result = Schedule::where('date', '=' , $request->input('date'))
            ->where('time_start', '<=', $request->input('hour_start'))
            ->where('time_end', '>=', $request->input('hour_end'))
            ->where('service_id', '=', $request->input('service_id'))
            ->get();

        if (count($result) == 0) {
            $response['error'] = true;
            $response['message'] = 'Could not be registered. There are not services for that day';

            return json_encode($response);
        }

        // comprobamos que la reserva no se solape con otra
        $result = DB::select(DB::raw("select * from (
            select * from reservations where date_reservation = '" . $request->input('date') . "' and service_id = " . $request->input('service_id') . " ) reserv
              where  (reserv.hour_end <= '" . $request->input('hour_start') . "' and '" . $request->input('hour_start') . "' <= reserv.hour_start) 
              or ('" . $request->input('hour_end') . "' >= reserv.hour_start and reserv.hour_end > '" . $request->input('hour_start') . "')"));

        if (count($result) > 0) {
            $response['error'] = true;
            $response['message'] = 'Could not be registered. Day and time overlapping ';
            
            return json_encode($response);
        }

        // salvamos la reserva
        $this->saveReservation($request);

        return json_encode($response);
    }

    private function saveReservation(Request $request) {
        $reservation = new Reservation();
        $reservation->total_price = $request->input('total_price');
        $reservation->comments = $request->input('comments');
        $reservation->customer_name = $request->input('customer_name');
        $reservation->date_reservation = date('Y-m-d', strtotime(str_replace('-', '/', $request->input('date'))));
        $reservation->hour_start = $request->input('hour_start');
        $reservation->hour_end = $request->input('hour_end');       
        $reservation->service_id = $request->input('service_id');
        $reservation->save();
    }
  
    // formateamos los datos por día y por reserva para tener unos datos
    // más amigables con los que tratar
    private function formatServices($data)
    {
        $result = [];

        if (!isset($data))
        {
            return $result;
        }

        foreach ($data as $value) 
        {
            $reservation = null;
            if (array_key_exists('hour_start', $value))
            {
                $reservation = array(
                    'hour_start' => $value->hour_start,
                    'hour_end' => $value->hour_end,
                    'serv_id' => $value->serv_id,
                    'name' => $value->name,
                    'date_reservation' => $value->date
                );
            }

           if(!array_key_exists($value->date, $result))
           {
                $result[$value->date] = array(
                    $value->serv_id => array(
                        'data' => $value, 
                        'reservations' => null,
                    )
                );
                if (isset($reservation))
                {
                    $result[$value->date][$value->serv_id]['reservations'] = $value->date_reservation != null ? array($reservation) : null;
                }
           }
           else
           {
                if(!array_key_exists( $value->serv_id, $result[$value->date]))
                {
                    $result[$value->date][$value->serv_id] = array(
                        'data' => $value, 
                        'reservations' =>  null
                    );
                    if (isset($reservation))
                    {
                        $result[$value->date][$value->serv_id]['reservations'] = $value->date_reservation != null ? array($reservation) : null;
                    }
                } 
                else
                {
                    if (isset($value->date_reservation))
                    {
                        array_push($result[$value->date][$value->serv_id]['reservations'], $reservation);
                    }
                }
           }
        }

        ksort($result);
        return $result;
    }
}
