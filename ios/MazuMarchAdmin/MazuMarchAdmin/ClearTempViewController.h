//
//  ClearTempViewController.h
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/3/29.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "Header.h"
#import "AFHTTPRequestOperationManager.h"

@interface ClearTempViewController : UIViewController


@property UIScrollView *scrollView;
@property NSMutableArray *list;

@property UILabel *labelAllJson;
@property UIButton *buttonAllJson;
@property UILabel *resultAllJson;

@property UILabel *labelPathJson;
@property UIButton *buttonPathJson;
@property UILabel *resultPathJson;

@property UILabel *labelMessageJson;
@property UIButton *buttonMessageJson;
@property UILabel *resultMessageJson;

@property UILabel *labelHeatmapJson;
@property UIButton *buttonHeatmapJson;
@property UILabel *resultHeatmapJson;

@property UILabel *labelTemp;
@property UIButton *buttonTemp;
@property UILabel *resultTemp;

@end
