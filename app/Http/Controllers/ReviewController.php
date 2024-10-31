<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'review' => 'required|string|min:2|max:2500',
        ]);

        Review::create([
            ...$data,
            'product_id' => $product->id,
            'user_id' => $request->user()->id,
            'rating' => 5,
        ]);

        return back();
    }

    public function update(Request $request, Review $review)
    {
        Gate::authorize('update', $review);

        if (!$review->canBeEditedOrDeleted()) {
            return response()->json(['message' => 'Editing is no longer allowed for this review.'], 403);
        }

        $data = $request->validate([
            'review' => 'required|string|min:2|max:2500',
        ]);

        $review->update($data);

        return back();
    }

    public function destroy(Review $review)
    {
        Gate::authorize('delete', $review);

        if (!$review->canBeEditedOrDeleted()) {
            return response()->json(['message' => 'Deleting is no longer allowed for this review.'], 403);
        }
        
        $review->delete();

        return back();
    }

   
}
