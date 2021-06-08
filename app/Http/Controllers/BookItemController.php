<?php

namespace App\Http\Controllers;

use App\BookItem;
use App\Http\Requests\BookItemRequest;
use App\Http\Requests\BookItemUpdate;
use App\Http\Requests\SearchRequest;
use App\Services\BookItemService;
use Illuminate\Http\Request;

class BookItemController extends Controller
{
    protected $bookItemService;
    public function __construct(BookItemService $bookItemService)
    {
        $this->bookItemService = $bookItemService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(BookItem::paginate(5));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookItemRequest $request)
    {
        $this->bookItemService->validateRequest($request);

        try{
           $result =  $this->bookItemService->create($request->validated());
           if($result === false){
               return response()->json(['status'=>'failed','message'=>'Duplicate record']);
           }
            return response()->json(['status'=>'ok','message'=>'Book item created successfully']);
        }catch (\Exception $exception){
            return response()->json(['status'=>'failed','error'=>$exception->getMessage()]);
        }




    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BookItem  $bookItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bookItem = BookItem::find($id);
        return response()->json($bookItem);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BookItem  $bookItem
     * @return \Illuminate\Http\Response
     */
    public function edit(BookItem $bookItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BookItem  $bookItem
     * @return \Illuminate\Http\Response
     */
    public function update(BookItemUpdate $request, $id)
    {
        BookItem::find($id)->update($request->validated());
        return response()->json(['status'=>'ok','message'=>'Book item updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BookItem  $bookItem
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        BookItem::find($id)->delete();
        return response()->json(['status'=>'ok','message'=>'Book item deleted successfully!']);
    }

    public function find(SearchRequest $request){
        $bookItems = BookItem::where('first_name','like','%'.$request->get('first_name').'%')->get();
        return response()->json($bookItems);
    }

}
