<?php

it('redirects to the ideas page', function () {
    $response = $this->get('/');

    $response->assertRedirectToRoute('ideas.index');
});
