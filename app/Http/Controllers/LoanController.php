<?php

namespace App\Http\Controllers;

use App\Http\Resources\LoanResource;
use App\Interfaces\LoanRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
{
    private LoanRepositoryInterface $loanRepository;

    public function __construct(LoanRepositoryInterface $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }

    public function showLoan(Request $request, $loanId)
    {
        $loan = $request->user()->loans()->find($loanId);

        if ($request->user()->cannot('view', $loan)) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        return new LoanResource($loan);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function createLoan(Request $request): JsonResponse
    {
        $message = 'Success';
        $loan = [];
        $params = $request->all();

        $validator = Validator::make($params, [
            'term' => 'required|integer|gt:0',
            'amount' => 'required|integer|gt:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        try {
            $loan = $this->loanRepository->createLoan($params);
            $statusCode = Response::HTTP_CREATED;
        } catch (\Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                $message = 'Customer not found';
            } else {
                $message = 'Failed to create new loan. Reason: ' . $e->getMessage();
            }
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json([
            'message' => $message,
            'loan' => new LoanResource($loan)
        ], $statusCode);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function addRepayment(Request $request, $id)
    {
        $params = $request->all();
        $repayment = [];
        $message = 'Success';

        $validator = Validator::make($params, [
            'amount' => 'required|integer|gt:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        try {
            $repayment = $this->loanRepository->addRepayment($params, $id);
            $statusCode = Response::HTTP_OK;
        } catch (\Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                $message = 'Repayment not found';
            } else {
                $message = 'Failed to update repayment. Reason: ' . $e->getMessage();
            }
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json([
            'message' => $message,
            'repayment' => $repayment
        ], $statusCode);
    }
}
