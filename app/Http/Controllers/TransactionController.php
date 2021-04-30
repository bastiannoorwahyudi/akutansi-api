<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Transaction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // menampilkan data transaksi berdasarkan waktu terbaru yang di input
        $transaction = Transaction::orderBy('time', 'DESC')->get();
        $response = [
            'message' => 'List transaction order by time',
            'data' => $transaction
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // melakukan proses validasi, apakah inputan sudah sesuai atau tidak
        $validator = Validator::make($request->all(), [
            'title' => ['required'],
            'amount' => ['required', 'numeric'],
            'type' => ['required', 'in:expense,revenue'],
        ]);

        // jika gagal
        if ($validator->fails()){
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // jika validasi berhasil
        try {
            // akan melakukan penyimpanan dengan query insert(create)
            $transaction = Transaction::create($request->all());

            // menampilkan respons jika data berhasil di simpan
            $response = [
                'message' => 'Transaction create',
                'data' => $transaction
            ];

            return response()->json($response, Response::HTTP_CREATED);

            //Jika gagal akan tampilkan pesan error melalui QueryException
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed" . $e->errorInfo
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // cek apakah data transakti dengan id tersebut ada atau tidak
        $transaction = Transaction::findOrFail($id);

        $response = [
            'message' => 'Detail of Transaction Resource',
            'data' => $transaction
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // cek apakah data transakti dengan id tersebut ada atau tidak
        $transaction = Transaction::findOrFail($id);

        // melakukan proses validasi, apakah inputan sudah sesuai atau tidak
        $validator = Validator::make($request->all(), [
            'title' => ['required'],
            'amount' => ['required', 'numeric'],
            'type' => ['required', 'in:expense,revenue'],
        ]);

        // jika gagal
        if ($validator->fails()){
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // jika validasi berhasil
        try {
            // lakukan update dengan query insert(update)
            $transaction->update($request->all());

            // menampilkan response jika data berhasil di simpan
            $response = [
                'message' => 'Transaction Update',
                'data' => $transaction
            ];

            return response()->json($response, Response::HTTP_OK);

            //Jika gagal akan tampilkan pesan error melalui QueryException
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed" . $e->errorInfo
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // cek apakah data transakti dengan id tersebut ada atau tidak
        $transaction = Transaction::findOrFail($id);

        try {
            // lakukan delete dengan query insert(update)
            $transaction->delete();

            // menampilkan response jika data berhasil di delete
            $response = [
                'message' => 'Transaction Delete'
            ];

            return response()->json($response, Response::HTTP_OK);

            //Jika gagal akan tampilkan pesan error melalui QueryException
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed" . $e->errorInfo
            ]);
        }
    }
}
