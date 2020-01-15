<?php

use RDStation\Helpers\Request;

trait MockRequest
{
    protected function getMockRequest()
    {
        return $this->createMock(Request::class);
    }

    protected function getMockRequestPost($accessToken, $refreshToken, $expireIn)
    {
        $request = $this->getMockRequest();
        $request->expects($this->once())
            ->method('post')
            ->willReturn([
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'expires_in' => $expireIn
            ]);

        return $request;
    }
}