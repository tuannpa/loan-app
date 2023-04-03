<?php

namespace App\Http\Controllers;

use App\Interfaces\AdminRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Routing\Controller;

class AdminController extends Controller
{
    private AdminRepositoryInterface $adminRepository;

    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    /**
     * @param $loanId
     * @return \Illuminate\Http\JsonResponse
     */
    public function approveLoan($loanId): JsonResponse
    {
        $loan = [];
        $message = 'Success';
        try {
            $loan = $this->adminRepository->approveLoan($loanId);
            $statusCode = Response::HTTP_OK;
        } catch (\Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                $message = 'Loan not found';
            } else {
                $message = 'Failed to approve the loan. Reason: ' . $e->getMessage();
            }
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json([
            'message' => $message,
            'loan' => $loan
        ], $statusCode);
    }
}
