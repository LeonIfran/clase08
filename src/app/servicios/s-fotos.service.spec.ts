import { TestBed } from '@angular/core/testing';

import { SFotosService } from './s-fotos.service';

describe('SFotosService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: SFotosService = TestBed.get(SFotosService);
    expect(service).toBeTruthy();
  });
});
