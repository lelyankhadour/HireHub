<?php

namespace Tests\Feature;

use App\Contracts\NotificationInterface;

use App\Enums\BidStatus;
use App\Enums\ProjectStatus;

use App\Models\Bid;
use App\Models\Project;
use App\Models\User;
use App\Services\BidService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Fakes\FakeNotifier;
use Tests\TestCase;

class BidServiceTest extends TestCase
{


    public function test_accept_sends_notification()
    {

        $fakeNotifier = new FakeNotifier();


        $this->app->singleton(NotificationInterface::class, function () use ($fakeNotifier) {
            return $fakeNotifier;
        });

        $service = $this->app->make(BidService::class);

        $client = User::factory()->create();
        $freelancer = User::factory()->create();

        $project = Project::factory()->create([
            'client_id' => $client->id,
            'status'    => ProjectStatus::Open,
        ]);

        $bid = Bid::factory()->create([
            'project_id'     => $project->id,
            'freelancer_id'  => $freelancer->id,
            'status'         => BidStatus::Pending,
        ]);

    
        $this->actingAs($client);

       
        $service->accept($bid);

        $this->assertCount(1, $fakeNotifier->sent);
        $this->assertEquals($freelancer->id, $fakeNotifier->sent[0]['user']->id);
        $this->assertStringContainsString('accepted', $fakeNotifier->sent[0]['message']);
    }
}
