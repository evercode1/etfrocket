<?php

namespace App\Http\Controllers\User\Portfolios;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\PortfolioTransaction;
use App\Services\PortfolioTransactions\CreatePortfolioTransactionService;
use App\Services\PortfolioTransactions\ListPortfolioTransactionsService;
use App\Services\PortfolioTransactions\UpdatePortfolioTransactionService;
use App\Services\PortfolioTransactions\ViewPortfolioTransactionFormService;
use App\Utilities\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PortfolioTransactionsController extends Controller
{
    public function listPortfolioTransactions(
        Request $request,
        int $portfolio_id,
        ListPortfolioTransactionsService $service
    ) {
        try {

            $transactions = $service->getData(Auth::id(), $portfolio_id, $request->input('etf_id'));

        } catch (\Exception $e) {

            Log::error('Failed to list portfolio transactions', [
                'user_id' => Auth::id(),
                'portfolio_id' => $portfolio_id,
                'etf_id' => $request->input('etf_id'),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Oops, something went wrong. Please try again later.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $transactions,
        ], 200);
    }

    public function getCreatePortfolioTransactionFormConfig(int $portfolio_id)
    {
        try {

            Portfolio::where('user_id', Auth::id())
                ->where('id', $portfolio_id)
                ->firstOrFail();

        } catch (\Exception $e) {

            Log::error('Failed to load create portfolio transaction form config', [
                'user_id' => Auth::id(),
                'portfolio_id' => $portfolio_id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Oops, something went wrong. Please try again later.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'portfolio_id' => $portfolio_id,
                'fields' => $this->transactionFormFields(),
            ],
        ], 200);
    }

    public function createPortfolioTransaction(
        Request $request,
        int $portfolio_id,
        CreatePortfolioTransactionService $service
    ) {
        $request->validate([
            'etf_id' => ['required', 'integer'],
            'transaction_type_id' => ['required', 'integer'],
            'shares' => ['required', 'numeric', 'gt:0'],
            'price_per_share' => ['required', 'numeric', 'gte:0'],
            'transaction_date' => ['required', 'date'],
        ]);

        try {

            $transaction = $service->create(
                Auth::id(),
                $portfolio_id,
                $request->all()
            );

        } catch (\Exception $e) {

            Log::error('Failed to create portfolio transaction', [
                'user_id' => Auth::id(),
                'portfolio_id' => $portfolio_id,
                'request' => $request->all(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Oops, something went wrong. Please try again later.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $transaction,
        ], 201);
    }

    public function getUpdatePortfolioTransactionFormConfig(
        int $id,
        ViewPortfolioTransactionFormService $service
    ) {
        try {

            $transaction = $service->getData(Auth::id(), $id);

        } catch (\Exception $e) {

            Log::error('Failed to load update portfolio transaction form config', [
                'user_id' => Auth::id(),
                'portfolio_transaction_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Oops, something went wrong. Please try again later.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'portfolio_transaction_id' => $transaction->id,
                'portfolio_id' => $transaction->portfolio_id,
                'fields' => $this->transactionFormFields($transaction),
            ],
        ], 200);
    }

    public function updatePortfolioTransaction(
        Request $request,
        int $id,
        UpdatePortfolioTransactionService $service
    ) {
        $request->validate([
            'etf_id' => ['sometimes', 'required', 'integer'],
            'transaction_type_id' => ['sometimes', 'required', 'integer'],
            'shares' => ['sometimes', 'required', 'numeric', 'gt:0'],
            'price_per_share' => ['sometimes', 'required', 'numeric', 'gte:0'],
            'transaction_date' => ['sometimes', 'required', 'date'],
        ]);

        try {

            $transaction = $service->update(
                Auth::id(),
                $id,
                $request->all()
            );

        } catch (\Exception $e) {

            Log::error('Failed to update portfolio transaction', [
                'user_id' => Auth::id(),
                'portfolio_transaction_id' => $id,
                'request' => $request->all(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Oops, something went wrong. Please try again later.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $transaction,
        ], 200);
    }

    public function deletePortfolioTransaction(int $id)
    {
        try {

            $transaction = PortfolioTransaction::query()
                ->select('portfolio_transactions.*')
                ->join('portfolios', 'portfolio_transactions.portfolio_id', '=', 'portfolios.id')
                ->where('portfolio_transactions.id', $id)
                ->where('portfolios.user_id', Auth::id())
                ->firstOrFail();

            $transaction->delete();

        } catch (\Exception $e) {

            Log::error('Failed to delete portfolio transaction', [
                'user_id' => Auth::id(),
                'portfolio_transaction_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Oops, something went wrong. Please try again later.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Portfolio transaction deleted successfully.',
        ], 200);
    }

    private function transactionFormFields(?PortfolioTransaction $transaction = null): array
    {
        return [
            [
                'name' => 'etf_id',
                'label' => 'ETF',
                'type' => 'select',
                'required' => true,
                'value' => $transaction?->etf_id,
            ],
            [
                'name' => 'transaction_type_id',
                'label' => 'Transaction Type',
                'type' => 'select',
                'required' => true,
                'value' => $transaction?->transaction_type_id,
            ],
            [
                'name' => 'shares',
                'label' => 'Shares',
                'type' => 'number',
                'required' => true,
                'step' => '0.0001',
                'min' => 0.0001,
                'value' => $transaction?->shares,
            ],
            [
                'name' => 'price_per_share',
                'label' => 'Price Per Share',
                'type' => 'number',
                'required' => true,
                'step' => '0.0001',
                'min' => 0,
                'value' => $transaction?->price_per_share,
            ],
            [
                'name' => 'transaction_date',
                'label' => 'Transaction Date',
                'type' => 'date',
                'required' => true,
                'value' => $transaction?->transaction_date?->format('Y-m-d'),
            ],
        ];
    }
}