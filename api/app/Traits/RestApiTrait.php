<?php

namespace App\Traits;

use Exception;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

trait RestApiTrait
{

    public static $entity;
    public static $validationRules;


/**
 * [store description]
 * @param  [type]  $data  [description]
 * @param  boolean $getId [description]
 * @return [type]         [Json HttpResponse || BigInteger]
 *
 * This method creates a new record for an entity. [Pass $getId = true  to retrieve ID of inserted record]
 */
    public function store($data, $getId = false)
    {
        if (RestApiTrait::$entity != null) {
            try {

                if ($getId) {
                    return  RestApiTrait::$entity::insertGetId($data);
                }

                $responseData = RestApiTrait::$entity::create($data);

                return response()->json([
                    "data" => $responseData,
                    "code" => "OK"
                ]);
            } catch (Exception $e) {
                throw $e;
            }
        }

        return new BadRequestHttpException('Empty Request');
    }

 /**
  * [list description]
  * @param  [type] $id   [description]
  * @param  array  $with [description]
  * @return [type]       [Json Http Response]
  *
  * This method handles list all and show for an entity
  */
    public function list($id = null, $with = [])
    {

        if (RestApiTrait::$entity != null) {
            try {
                $responseData = ($id) ? RestApiTrait::$entity::with($with)->findOrFail($id) : RestApiTrait::$entity::with($with)->get();
                return response()->json([
                    "data" => $responseData,
                    "code" => "OK"
                ]);
            } catch (Exception $e) {
                throw $e;
            }
        }else{
            throw new BadRequestHttpException('Invalid Entity!');
        }
    }


    /**
     * [update description]
     * @param  [type] $id   [description]
     * @param  [type] $data [description]
     * @return [type]       [Json HttpResponse]
     *
     * This method updates an entity
     */
        public function update($id, $data)
        {

            if (RestApiTrait::$entity != null) {
                try {
                    RestApiTrait::$entity::findOrFail($id)->update($data);
                    return response()->json([
                        "data" => RestApiTrait::$entity::findOrFail($id),
                        "code" => "OK"
                    ]);
                } catch (Exception $e) {
                    throw $e;
                }
            }
        }



    /**
     * [delete description]
     * @param  [type] $id [description]
     * @return [type]     [Json HttpResponse]
     *
     * This method deletes an entity
     */
        public function delete($id)
        {

            if (RestApiTrait::$entity != null) {
                try {
                    $deleted = RestApiTrait::$entity::findOrFail($id)->delete();
                    return response()->json([
                        "data" => $deleted,
                        "code" => "OK"
                    ]);
                } catch (Exception $e) {
                    throw $e;
                }
            }
        }


/**
 * [toggle description]
 * @param  [type] $id   [description]
 * @param  [type] $data [description]
 * @return [type]       [Json HttpResponse]
 *
 * This method can be used to update an entity [though the main aim is to toggle it's status]
 */
    public function toggle($id, $data)
    {

        if (RestApiTrait::$entity != null) {
            try {
                RestApiTrait::$entity::findOrFail($id)->update($data);
                return response()->json([
                    "data" => RestApiTrait::$entity::findOrFail($id),
                    "code" => "OK"
                ]);
            } catch (Exception $e) {
                throw $e;
            }
        }
    }



}
